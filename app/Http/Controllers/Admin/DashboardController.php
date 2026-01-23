<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'products' => Product::count(),
            'orders' => Order::count(),
            'revenue' => Order::where('status', 'delivered')->sum('total_amount'),
        ];
        return view('admin.dashboard', compact('stats'));
    }


    public function products()
    {
        $products = Product::with('category', 'seller')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function orders()
    {
        $orders = Order::with('user')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function categories()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }
}
