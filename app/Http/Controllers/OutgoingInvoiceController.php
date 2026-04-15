<?php

namespace App\Http\Controllers;

use App\Models\OutgoingInvoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutgoingInvoiceController extends Controller
{
    public function index()
    {
        $invoices = OutgoingInvoice::with(['user'])
            ->latest()
            ->paginate(15);

        return view('outgoing.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $products = Product::with('unit')
            ->where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();

        $number = OutgoingInvoice::generateNumber();

        return view('outgoing.invoices.create', compact('products', 'number'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_date'       => 'required|date',
            'recipient'          => 'required|string|max:255',
            'comment'            => 'nullable|string',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|numeric|min:0.01',
        ]);

        $invoice = OutgoingInvoice::create([
            'invoice_number' => OutgoingInvoice::generateNumber(),
            'invoice_date'   => $request->invoice_date,
            'recipient'      => $request->recipient,
            'user_id'        => auth()->id(),
            'comment'        => $request->comment,
            'status'         => 'draft',
        ]);

        foreach ($request->items as $item) {
            $invoice->items()->create([
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
            ]);
        }

        return redirect()->route('outgoing.invoices.show', $invoice)
            ->with('success', 'Расходная накладная создана. Статус: черновик.');
    }

    public function show(OutgoingInvoice $invoice)
    {
        $invoice->load(['user', 'items.product.unit']);
        return view('outgoing.invoices.show', compact('invoice'));
    }

    public function edit(OutgoingInvoice $invoice)
    {
        $this->authorize('update', $invoice);

        $products = Product::with('unit')
            ->orderBy('name')
            ->get();

        $invoice->load('items');

        return view('outgoing.invoices.edit', compact('invoice', 'products'));
    }

    public function update(Request $request, OutgoingInvoice $invoice)
    {
        $this->authorize('update', $invoice);

        $request->validate([
            'invoice_date'       => 'required|date',
            'recipient'          => 'required|string|max:255',
            'comment'            => 'nullable|string',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|numeric|min:0.01',
        ]);

        $invoice->update([
            'invoice_date' => $request->invoice_date,
            'recipient'    => $request->recipient,
            'comment'      => $request->comment,
        ]);

        $invoice->items()->delete();

        foreach ($request->items as $item) {
            $invoice->items()->create([
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
            ]);
        }

        return redirect()->route('outgoing.invoices.show', $invoice)
            ->with('success', 'Накладная обновлена.');
    }

    public function destroy(OutgoingInvoice $invoice)
    {
        $this->authorize('delete', $invoice);

        $invoice->delete();

        return redirect()->route('outgoing.invoices.index')
            ->with('success', 'Накладная удалена.');
    }
    public function post(OutgoingInvoice $invoice)
    {
        $this->authorize('post', $invoice);

        if (!$invoice->isDraft()) {
            return redirect()->back()
                ->with('error', 'Провести можно только накладную в статусе "Черновик".');
        }

        $invoice->load('items.product.unit');

        foreach ($invoice->items as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                return redirect()->back()
                    ->with('error',
                        "Недостаточно товара «{$item->product->name}»: " .
                        "на складе {$item->product->stock_quantity} {$item->product->unit->abbreviation}, " .
                        "запрошено {$item->quantity}."
                    );
            }
        }

        DB::transaction(function () use ($invoice) {
            foreach ($invoice->items as $item) {
                $item->product->decrement('stock_quantity', $item->quantity);
            }
            $invoice->update(['status' => 'posted']);
        });

        return redirect()->route('outgoing.invoices.show', $invoice)
            ->with('success', 'Накладная проведена. Остатки товаров уменьшены.');
    }

    public function cancel(OutgoingInvoice $invoice)
    {
        $this->authorize('cancel', $invoice);

        if (!$invoice->isPosted()) {
            return redirect()->back()
                ->with('error', 'Отменить можно только проведённую накладную.');
        }

        $invoice->load('items.product');

        DB::transaction(function () use ($invoice) {
            foreach ($invoice->items as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
            $invoice->update(['status' => 'cancelled']);
        });

        return redirect()->route('outgoing.invoices.show', $invoice)
            ->with('success', 'Накладная отменена. Товары возвращены на склад.');
    }
}
