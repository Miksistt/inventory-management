<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy('name')->paginate(10);
        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'abbreviation' => 'required|string|max:10|unique:units',
        ]);

        Unit::create($validated);

        return redirect()->route('admin.units.index')->with('success', 'Единица измерения создана');
    }

    public function show(Unit $unit)
    {
        return view('admin.units.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'abbreviation' => 'required|string|max:10|unique:units,abbreviation,' . $unit->id,
        ]);

        $unit->update($validated);

        return redirect()->route('admin.units.index')->with('success', 'Единица измерения обновлена');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('admin.units.index')->with('success', 'Единица измерения удалена');
    }
}
