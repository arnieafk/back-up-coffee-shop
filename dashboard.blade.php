@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    @php
        $paymentsHealthClass = $ordersWithPaymentsPercent >= 80 ? 'health-good' : ($ordersWithPaymentsPercent >= 50 ? 'health-warn' : 'health-bad');
        $itemsHealthClass = $ordersWithItemsPercent >= 80 ? 'health-good' : ($ordersWithItemsPercent >= 50 ? 'health-warn' : 'health-bad');
    @endphp

    <h1 class="title">Coffee Shop Dashboard (UML Based)</h1>
    <p class="subtitle">Central view for Admin management of Staff, Menu, Inventory, Orders, Customers, and Payments.</p>

    <div class="cards" style="margin-bottom: 20px;">
        <div class="card">
            <h2>{{ $totalUsers }}</h2>
            <p class="muted">Users (System Accounts)</p>
        </div>
        <div class="card">
            <h2>{{ $totalStaff }}</h2>
            <p class="muted">Staff</p>
        </div>
        <div class="card">
            <h2>{{ $totalCustomers }}</h2>
            <p class="muted">Customers</p>
        </div>
        <div class="card">
            <h2>{{ $totalOrders }}</h2>
            <p class="muted">Total Orders</p>
        </div>
        <div class="card">
            <h2>{{ $pendingOrders }}</h2>
            <p class="muted">Pending/Preparing Orders</p>
        </div>
        <div class="card">
            <h2>{{ $totalOrderItems }}</h2>
            <p class="muted">Order Items</p>
        </div>
        <div class="card">
            <h2>{{ $totalMenuItems }}</h2>
            <p class="muted">Menu Items</p>
        </div>
        <div class="card">
            <h2>{{ $lowStockCount }}</h2>
            <p class="muted">Low Stock Inventory</p>
        </div>
        <div class="card">
            <h2>{{ $totalPayments }}</h2>
            <p class="muted">Payments</p>
        </div>
        <div class="card">
            <h2>{{ number_format((float) $totalRevenue, 2) }}</h2>
            <p class="muted">Total Revenue</p>
        </div>
    </div>

    <div class="nav">
        <a href="{{ route('admin.users.index') }}">Manage Users</a>
        <a href="{{ route('admin.staff.index') }}">Manage Staff</a>
        <a href="{{ route('admin.inventory.index') }}">Manage Inventory</a>
        <a href="{{ route('admin.reports.index') }}">View Reports</a>
        <a href="{{ route('admin.menu.index') }}">Manage Menu</a>
    </div>

    <h2 style="margin-top: 20px;">Relationship Counts</h2>
    <div class="cards" style="margin-bottom: 20px;">
        <div class="card">
            <h3>{{ $customerToOrderCount }}</h3>
            <p class="muted">Customer → Order</p>
        </div>
        <div class="card">
            <h3>{{ $orderToOrderItemCount }}</h3>
            <p class="muted">Order → OrderItem</p>
        </div>
        <div class="card">
            <h3>{{ $orderToPaymentCount }}</h3>
            <p class="muted">Order → Payment</p>
        </div>
        <div class="card">
            <h3>{{ $ordersWithPayments }}</h3>
            <p class="muted">Orders with Payments ({{ $ordersWithPaymentsPercent }}%)</p>
            <span class="health-pill {{ $paymentsHealthClass }}">
                {{ $ordersWithPaymentsPercent >= 80 ? 'Healthy' : ($ordersWithPaymentsPercent >= 50 ? 'Watch' : 'Critical') }}
            </span>
        </div>
        <div class="card">
            <h3>{{ $ordersWithItems }}</h3>
            <p class="muted">Orders with at Least One Item ({{ $ordersWithItemsPercent }}%)</p>
            <span class="health-pill {{ $itemsHealthClass }}">
                {{ $ordersWithItemsPercent >= 80 ? 'Healthy' : ($ordersWithItemsPercent >= 50 ? 'Watch' : 'Critical') }}
            </span>
        </div>
    </div>

    <h2 style="margin-top: 20px;">Recent Orders</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Staff</th>
            <th>Date</th>
            <th>Status</th>
            <th>Total</th>
        </tr>
        @forelse($recentOrders as $order)
            <tr>
                <td>{{ $order->orderID }}</td>
                <td>{{ $order->customer?->name ?? 'N/A' }}</td>
                <td>{{ $order->staff?->name ?? 'Unassigned' }}</td>
                <td>{{ optional($order->orderDate)->format('Y-m-d H:i') }}</td>
                <td>{{ $order->status }}</td>
                <td>{{ number_format((float) $order->totalAmount, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="muted">No orders found.</td>
            </tr>
        @endforelse
    </table>

    <form method="POST" action="{{ route('logout') }}" style="margin-top: 16px;">
        @csrf
        <button type="submit" class="btn-delete">Logout</button>
    </form>
@endsection
