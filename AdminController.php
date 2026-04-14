<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Staff;
use App\Models\User;

class AdminController extends Controller
{
    // Middleware
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /*
    |----------------------------------------
    | DASHBOARD
    |----------------------------------------
    */
    public function dashboard()
    {
        $totalUsers = User::query()->count();
        $totalStaff = Staff::query()->count();
        $totalCustomers = Customer::query()->count();
        $totalOrders = Order::query()->count();
        $pendingOrders = Order::query()
            ->whereIn('status', ['Pending', 'Preparing'])
            ->count();
        $totalOrderItems = OrderItem::query()->count();
        $totalMenuItems = MenuItem::query()->count();
        $totalPayments = Payment::query()->count();
        $totalRevenue = (float) Payment::query()->sum('amount');
        $lowStockCount = Inventory::query()
            ->whereColumn('stockQuantity', '<=', 'reorderLevel')
            ->count();

        $recentOrders = Order::query()
            ->with(['customer', 'staff'])
            ->latest('orderDate')
            ->limit(8)
            ->get();

        $customerToOrderCount = Order::query()->whereNotNull('customerID')->count();
        $orderToOrderItemCount = OrderItem::query()->whereNotNull('orderID')->count();
        $orderToPaymentCount = Payment::query()->whereNotNull('orderID')->count();
        $ordersWithPayments = Order::query()->has('payments')->count();
        $ordersWithItems = Order::query()->has('orderItems')->count();
        $ordersWithPaymentsPercent = $totalOrders > 0 ? round(($ordersWithPayments / $totalOrders) * 100, 1) : 0;
        $ordersWithItemsPercent = $totalOrders > 0 ? round(($ordersWithItems / $totalOrders) * 100, 1) : 0;

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalStaff',
            'totalCustomers',
            'totalOrders',
            'pendingOrders',
            'totalOrderItems',
            'totalMenuItems',
            'totalPayments',
            'totalRevenue',
            'lowStockCount',
            'recentOrders',
            'customerToOrderCount',
            'orderToOrderItemCount',
            'orderToPaymentCount',
            'ordersWithPayments',
            'ordersWithItems',
            'ordersWithPaymentsPercent',
            'ordersWithItemsPercent'
        ));
    }

    /*
    |----------------------------------------
    | USERS MANAGEMENT
    |----------------------------------------
    */
    public function users()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->all());

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /*
    |----------------------------------------
    | REPORTS
    |----------------------------------------
    */
    public function reports()
    {
        $orders = Order::with('customer')->get();

        return view('admin.reports.index', compact('orders'));
    }
}
