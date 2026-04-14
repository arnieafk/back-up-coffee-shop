<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Inventory;
use App\Models\Payment;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'paymentType' => 'required|string',
        ]);

        $cartItems = [];
        if ($request->filled('cart')) {
            $cartItems = json_decode((string) $request->input('cart'), true) ?? [];
        } else {
            $itemIds = (array) $request->input('itemID', []);
            $quantities = (array) $request->input('quantity', []);

            foreach ($itemIds as $index => $itemId) {
                $cartItems[] = [
                    'id' => (int) $itemId,
                    'qty' => (int) ($quantities[$index] ?? 0),
                ];
            }
        }

        $cartItems = array_values(array_filter($cartItems, fn (array $item): bool => (int) ($item['qty'] ?? 0) > 0));

        if ($cartItems === []) {
            return back()->withErrors(['cart' => 'Please select at least one item quantity.']);
        }

        DB::transaction(function () use ($request, $cartItems) {
            $cart = $cartItems;

            $order = Order::create([
                'customerID' => auth()->id(),
                'staffID' => null,
                'orderDate' => now(),
                'status' => 'Pending',
                'totalAmount' => 0,
            ]);

            $total = 0;

            foreach ($cart as $item) {
                $menu = MenuItem::query()->findOrFail($item['id']);
                $qty = (int) ($item['qty'] ?? 0);

                if ($qty <= 0) {
                    continue;
                }

                $inventory = Inventory::query()->where('itemID', $menu->itemID)->first();
                if ($inventory && $inventory->stockQuantity < $qty) {
                    throw new \RuntimeException('Insufficient stock for item '.$menu->itemID);
                }

                $subtotal = (float) ($menu->price * $qty);
                $total += $subtotal;

                OrderItem::create([
                    'orderID' => $order->orderID,
                    'itemID' => $menu->itemID,
                    'quantity' => $qty,
                    'price' => $menu->price,
                ]);

                if ($inventory) {
                    $inventory->updateStock(-$qty);
                }
            }

            $order->update(['totalAmount' => $total]);

            $payment = Payment::create([
                'orderID' => $order->orderID,
                'paymentType' => $request->paymentType,
                'amount' => $total,
                'paymentDate' => null,
            ]);

            $payment->processPayment();
        });

        return redirect()->route('customer.orders')->with('success', 'Order placed successfully.');
    }
}
