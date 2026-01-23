<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty()) return redirect()->route('cart.index');
        
        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        return view('buyer.checkout', compact('cartItems', 'total'));
    }

    public function process(Request $request)
    {
        
        $cartItems = Cart::where('user_id', Auth::id())->get();
        if ($cartItems->isEmpty()) return back();

        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->with('error', "Sorry, {$item->product->name} is out of stock or requested quantity unavailable.");
            }
        }

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'user_id' => Auth::id(),
            'total_amount' => $total,
            'status' => 'pending_verification',
            'shipping_address' => json_encode($request->only('address', 'city', 'state', 'zip', 'country', 'phone')),
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        // Clear cart
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('orders.show', $order);
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->paginate(10);
        return view('buyer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);
        $order->load('items.product');
        return view('buyer.orders.show', compact('order'));
    }
}
