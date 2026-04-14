<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\MenuItem;
use App\Models\Payment;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $customerId = (int) Auth::id();

        $totalOrders = Order::query()->where('customerID', $customerId)->count();
        $pendingOrders = Order::query()
            ->where('customerID', $customerId)
            ->whereIn('status', ['Pending', 'Preparing'])
            ->count();
        $totalSpent = (float) Order::query()->where('customerID', $customerId)->sum('totalAmount');
        $availableItems = MenuItem::query()->count();

        return view('customer.dashboard', compact('totalOrders', 'pendingOrders', 'totalSpent', 'availableItems'));
    }

    public function menu()
    {
        $menuItems = MenuItem::query()
            ->with('inventory')
            ->orderBy('category')
            ->orderBy('itemName')
            ->get();

        return view('customer.menu', compact('menuItems'));
    }

    public function orders()
    {
        $orders = Order::with('orderItems')
            ->where('customerID', Auth::id())
            ->latest('orderDate')
            ->get();

        return view('customer.orders', compact('orders'));
    }

    public function payments()
    {
        $payments = Payment::query()
            ->whereHas('order', function ($query): void {
                $query->where('customerID', Auth::id());
            })
            ->with('order')
            ->latest('paymentDate')
            ->get();

        return view('customer.payments', compact('payments'));
    }

    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $customer = Customer::query()->find($user->id);

        return view('customer.profile', compact('user', 'customer'));
    }
}
