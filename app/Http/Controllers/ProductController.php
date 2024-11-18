<?php
namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(25);
        $noProducts = $products->isEmpty();

        return view('products.index', compact('products', 'noProducts'));
    }

    public function fetchProducts()
    {
        \App\Jobs\RetrieveProductsJob::dispatch();

        return redirect()->route('products.index')->with('success', 'Product retrieval job started.');
    }
}