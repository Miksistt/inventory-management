<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'unit']);

        if ($request->filled('category')) {$query->where('category_id', $request->input('category'));}

        if ($request->filled('search')) { $search = $request->input('search');
            $query->where(function ($q) use ($search) { $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('stock_filter')) {
            $filter = $request->input('stock_filter');
            if ($filter === 'critical') {$query->where('stock_quantity', 0);}
            elseif ($filter === 'low') {$query->whereColumn('stock_quantity', '<=', 'min_stock')
                    ->where('stock_quantity', '>', 0);}
            elseif ($filter === 'normal') {$query->whereColumn('stock_quantity', '>', 'min_stock');}
        }

        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');
        $query->orderBy($sort, $direction);

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('stock.index', compact('products', 'categories'));
    }
}
