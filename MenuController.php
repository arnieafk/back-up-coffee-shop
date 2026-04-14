<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;

class MenuController extends Controller
{
    public function index()
    {
        $menus = MenuItem::all();
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'itemName' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
        ]);

        MenuItem::create([
            'itemName' => $request->itemName,
            'category' => $request->category,
            'price' => $request->price,
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu added!');
    }

    public function edit($id)
    {
        $menu = MenuItem::findOrFail($id);
        return view('admin.menu.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $menu = MenuItem::findOrFail($id);

        $menu->update([
            'itemName' => $request->itemName,
            'category' => $request->category,
            'price' => $request->price,
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu updated!');
    }

    public function destroy($id)
    {
        MenuItem::destroy($id);
        return back()->with('success', 'Deleted!');
    }
}
