<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\DemoMail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']); // Removed 'payment' as it is not a relationship
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending_verification,verified,shipped,delivered,cancelled',
        ]);

        // Check if status is changing to shipped or delivered for the first time
        if (!in_array($order->status, ['shipped', 'delivered']) && in_array($request->status, ['shipped', 'delivered'])) {
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->decrement('stock', $item->quantity);
                }
            }
        }
        
        if ($request->status === 'shipped' && $order->status !== 'shipped') {
             $mailData = [
                'title' => 'Order Shipped - Haven & Hue',
                'body' => 'Your order has been shipped and will be delivered within 5-6 working days.',
            ];
            Mail::to($order->user->email)->send(new DemoMail($mailData));
        }

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
}
