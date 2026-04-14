<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\MenuItem;

class StaffController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        return view('staff.dashboard');
    }

    // Orders Page
    public function orders()
    {
        $orders = Order::query()->latest('orderDate')->get();
        return view('staff.orders', compact('orders'));
    }

    // Menu Page
    public function menu()
    {
        $menuItems = MenuItem::all();
        return view('staff.menu', compact('menuItems'));
    }

    // Reports Page
    public function reports()
    {
        $totalOrders = Order::count();
        $totalSales = Order::sum('totalAmount');

        return view('staff.reports', compact('totalOrders', 'totalSales'));
    }
    // Complete Order
public function completeOrder($id)
{
    $order = Order::findOrFail($id);
    $order->updateStatus('Completed');

    return redirect()->back();
}

// Store Menu
public function storeMenu(Request $request)
{
    $request->validate([
        'itemName' => 'required|string',
        'category' => 'required|string',
        'price' => 'required|numeric',
    ]);

    MenuItem::create([
        'itemName' => $request->itemName,
        'category' => $request->category,
        'price' => $request->price,
    ]);
    return redirect()->back();
}

// Delete Menu
public function deleteMenu($id)
{
    MenuItem::findOrFail($id)->delete();
    return redirect()->back();
}
}
