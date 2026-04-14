<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class ReportController extends Controller
{
    public function index()
    {
        $totalSales = Order::sum('totalAmount');
        $totalOrders = Order::count();
        $totalItems = OrderItem::count();

        return view('admin.reports.index', compact('totalSales', 'totalOrders', 'totalItems'));
    }
}
