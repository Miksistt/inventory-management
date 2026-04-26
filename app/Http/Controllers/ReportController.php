<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\IncomingInvoice;
use App\Models\OutgoingInvoice;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function stock(Request $request)
    {
        $query = Product::with(['category', 'unit']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('stock_filter')) {
            $filter = $request->input('stock_filter');
            if ($filter === 'zero') {
                $query->where('stock_quantity', 0);
            } elseif ($filter === 'low') {
                $query->whereNotNull('min_stock')
                    ->whereColumn('stock_quantity', '<=', 'min_stock')
                    ->where('stock_quantity', '>', 0);
            } elseif ($filter === 'normal') {
                $query->where(function ($q) {
                    $q->whereNull('min_stock')
                        ->orWhereColumn('stock_quantity', '>', 'min_stock');
                });
            }
        }

        $products    = $query->orderBy('name')->get();
        $categories  = Category::orderBy('name')->get();
        $totalItems  = $products->count();
        $zeroStock   = $products->where('stock_quantity', 0)->count();

        return view('reports.stock', compact(
            'products', 'categories', 'totalItems', 'zeroStock'
        ));
    }

    public function incoming(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to   = $request->input('to', now()->format('Y-m-d'));

        $invoices = IncomingInvoice::with(['supplier', 'user'])
            ->whereBetween('invoice_date', [$from, $to])
            ->where('status', 'posted')
            ->orderBy('invoice_date', 'desc')
            ->get();

        $totalAmount = $invoices->sum('total_amount');
        $totalCount  = $invoices->count();

        return view('reports.incoming', compact(
            'invoices', 'totalAmount', 'totalCount', 'from', 'to'
        ));
    }

    public function outgoing(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to   = $request->input('to', now()->format('Y-m-d'));

        $invoices = OutgoingInvoice::with(['user', 'items.product'])
            ->whereBetween('invoice_date', [$from, $to])
            ->where('status', 'posted')
            ->orderBy('invoice_date', 'desc')
            ->get();

        $totalCount = $invoices->count();
        $totalItems = $invoices->sum(fn($inv) => $inv->items->sum('quantity'));

        return view('reports.outgoing', compact(
            'invoices', 'totalCount', 'totalItems', 'from', 'to'
        ));
    }

    public function suppliers(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to   = $request->input('to', now()->format('Y-m-d'));

        $suppliers = Supplier::withCount([
            'incomingInvoices as invoices_count' => function ($q) use ($from, $to) {
                $q->whereBetween('invoice_date', [$from, $to])
                    ->where('status', 'posted');
            },
        ])
            ->withSum([
                'incomingInvoices as total_amount' => function ($q) use ($from, $to) {
                    $q->whereBetween('invoice_date', [$from, $to])
                        ->where('status', 'posted');
                },
            ], 'total_amount')
            ->orderByDesc('total_amount')
            ->get();

        return view('reports.suppliers', compact('suppliers', 'from', 'to'));
    }
}
