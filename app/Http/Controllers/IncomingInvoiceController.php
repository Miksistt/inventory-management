<?php

namespace App\Http\Controllers;

use App\Models\IncomingInvoice;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $this->authorize('update', $invoice);

        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::with('unit')->orderBy('name')->get();
        $invoice->load('items');

        return view('incoming.invoices.edit', compact('invoice', 'suppliers', 'products'));
    }

    public function update(Request $request, IncomingInvoice $invoice)
    {
        $this->authorize('update', $invoice);

        $request->validate([
            'invoice_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'comment' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice->update([
            'invoice_date' => $request->invoice_date,
            'supplier_id' => $request->supplier_id,
            'comment' => $request->comment,
        ]);

        $invoice->items()->delete();

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
            ->with('success', 'Накладная обновлена.');
    }

    public function destroy(IncomingInvoice $invoice)
    {
        $this->authorize('delete', $invoice);
        $invoice->delete();
        return redirect()->route('incoming.invoices.index')
            ->with('success', 'Накладная удалена.');
    }

    public function post(IncomingInvoice $invoice)
    {
        $this->authorize('post', $invoice);

        if (!$invoice->isDraft()) {
            return redirect()->back()
                ->with('error', 'Провести можно только накладную в статусе "Черновик".');
        }

        $invoice->load('items.product');

        DB::transaction(function () use ($invoice) {
            foreach ($invoice->items as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
            $invoice->update(['status' => 'posted']);
        });

        return redirect()->route('incoming.invoices.show', $invoice)
            ->with('success', 'Накладная проведена. Остатки товаров увеличены.');
    }

    public function cancel(IncomingInvoice $invoice)
    {
        $this->authorize('cancel', $invoice);

        if (!$invoice->isPosted()) {
            return redirect()->back()
                ->with('error', 'Отменить можно только проведённую накладную.');
        }

        $invoice->load('items.product');

        DB::transaction(function () use ($invoice) {
            foreach ($invoice->items as $item) {
                $item->product->decrement('stock_quantity', $item->quantity);
            }
            $invoice->update(['status' => 'cancelled']);
        });

        return redirect()->route('incoming.invoices.show', $invoice)
            ->with('success', 'Накладная отменена. Остатки товаров скорректированы.');
    }
}
