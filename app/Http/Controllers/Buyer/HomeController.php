<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();
            $featuredProducts = Product::with('primaryImage', 'category')->latest()->take(8)->get();

            if ($categories->isEmpty() || $featuredProducts->isEmpty()) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                $categories = Category::all();
                $featuredProducts = Product::with('primaryImage', 'category')->latest()->take(8)->get();
            }
        } catch (\Throwable $e) {
            try {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                $categories = Category::all();
                $featuredProducts = Product::with('primaryImage', 'category')->latest()->take(8)->get();
            } catch (\Throwable $ex) {
                $featuredProducts = collect();
                $categories = collect();
            }
        }
        return view('buyer.home', compact('featuredProducts', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load('images', 'category', 'seller');
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();
        return view('buyer.products.show', compact('product', 'relatedProducts'));
    }

    public function category(Category $category)
    {
        $products = $category->products()->paginate(12);
        return view('buyer.products.index', compact('products', 'category'));
    }
}
