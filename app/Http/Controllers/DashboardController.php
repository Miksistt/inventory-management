<?php

namespace App\Http\Controllers;

use App\Models\IncomingInvoice;
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


        $criticalProducts = Product::with(['category', 'unit'])
            ->where('stock_quantity', '<=', 0)
            ->orWhereColumn('stock_quantity', '<=', 'min_stock')
            ->limit(10)
            ->get();


        $stats = [
            'products_count' => Product::count(),
            'low_stock_count' => Product::whereColumn('stock_quantity', '<=', 'min_stock')->count(),
            'invoices_today' => IncomingInvoice::whereDate('created_at', today())->count(),
        ];

        return view('dashboard', compact('recentIncoming', 'criticalProducts', 'stats'));
    }
}
