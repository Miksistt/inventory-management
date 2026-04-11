<?php

namespace App\Http\Controllers;

use App\Models\IncomingInvoice;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class IncomingInvoiceController extends Controller
{
    public function index()
    {
        $invoices = IncomingInvoice::with(['supplier', 'user'])
            ->latest()
            ->paginate(15);

        return view('incoming.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::with('unit')->orderBy('name')->get();
        $number = IncomingInvoice::generateNumber();

        return view('incoming.invoices.create', compact('suppliers', 'products', 'number'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'comment' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice = IncomingInvoice::create([
            'invoice_number' => IncomingInvoice::generateNumber(),
            'invoice_date' => $request->invoice_date,
            'supplier_id' => $request->supplier_id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
            'status' => 'draft',
            'total_amount' => 0,
        ]);

        $total = 0;
        foreach ($request->items as $item) {
            $lineTotal = $item['quantity'] * $item['unit_price'];
            $total += $lineTotal;
            $invoice->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $lineTotal,
            ]);
        }

        $invoice->update(['total_amount' => $total]);

        return redirect()->route('incoming.invoices.show', $invoice)
            ->with('success', 'Приходная накладная создана. Статус: черновик.');
    }

    public function show(IncomingInvoice $invoice)
    {
        $invoice->load(['supplier', 'user', 'items.product.unit']);
        return view('incoming.invoices.show', compact('invoice'));
    }

    public function edit(IncomingInvoice $invoice)
    {
        // Пока пропустим, добавим позже если нужно
    }

    public function update(Request $request, IncomingInvoice $invoice)
    {
        // Пока пропустим
    }

    public function destroy(IncomingInvoice $invoice)
    {
        // Пока пропустим
    }
}
