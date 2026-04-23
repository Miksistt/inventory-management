<?php

namespace App\Http\Controllers;

use App\Models\IncomingInvoice;
use App\Models\OutgoingInvoice;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $recentIncoming = IncomingInvoice::with('supplier')
            ->latest()
            ->limit(5)
            ->get();

        $recentOutgoing = OutgoingInvoice::with('user')
            ->latest()
            ->limit(5)
            ->get();

        $criticalProducts = Product::with(['category', 'unit'])
            ->where(function ($q) {
                $q->where('stock_quantity', 0)
                    ->orWhere(function ($q2) {
                        $q2->whereNotNull('min_stock')
                            ->whereColumn('stock_quantity', '<=', 'min_stock');
                    });
            })
            ->limit(10)
            ->get();

        $stats = [
            'products_count' => Product::count(),
            'low_stock_count' => Product::where(function ($q) {
                $q->where('stock_quantity', 0)
                    ->orWhere(function ($q2) {
                        $q2->whereNotNull('min_stock')
                            ->whereColumn('stock_quantity', '<=', 'min_stock');
                    });
            })->count(),
            'invoices_today' => IncomingInvoice::whereDate('created_at', today())->count(),
            'outgoing_today' => OutgoingInvoice::whereDate('created_at', today())->count(),
        ];

        return view('dashboard', compact('recentIncoming', 'recentOutgoing', 'criticalProducts', 'stats'));
    }
}
