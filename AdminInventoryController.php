<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\MenuItem;

class AdminInventoryController extends Controller
{
    // DISPLAY
    public function index()
    {
        $menuItems = MenuItem::query()->orderBy('itemName')->get();
        $items = Inventory::with('menuItem')->get();
        return view('admin.inventory.index', compact('items', 'menuItems'));
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'itemID' => 'required|integer|exists:menu_items,itemID|unique:inventories,itemID',
            'stockQuantity' => 'required|integer|min:0',
            'reorderLevel' => 'required|integer|min:0',
        ]);

        Inventory::create([
            'itemID' => $request->itemID,
            'stockQuantity' => $request->stockQuantity,
            'reorderLevel' => $request->reorderLevel,
            'lastUpdated' => now()
        ]);

        return back()->with('success', 'Inventory added');
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'stockQuantity' => 'required|integer|min:0',
            'reorderLevel' => 'required|integer|min:0',
        ]);

        $item = Inventory::findOrFail($id);

        $item->update([
            'stockQuantity' => $request->stockQuantity,
            'reorderLevel' => $request->reorderLevel,
            'lastUpdated' => now()
        ]);

        return back()->with('success', 'Inventory updated');
    }

    // DELETE
    public function destroy($id)
    {
        Inventory::destroy($id);
        return back()->with('success', 'Deleted');
    }
}
