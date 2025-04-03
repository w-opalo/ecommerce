<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductListResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use Spatie\Permission\Commands\Show;
use App\Http\Resources\ProductResource; // Ensure this file exists in the specified namespace
use App\Http\Resources\ProductsResource;

class ProductController extends Controller
{
    public function index()
    {
        // Return a view or JSON response
        return view('products.index'); // Example: Adjust as needed
    }

    public function home()
    {
        $products = Product::query()
            ->published()
            ->paginate(12);
        return Inertia::render('home', [
            'products' => ProductListResource::collection($products),
        ]);
    }

    public function show(Product $product)
    {
        return Inertia::render('Product/Show', [
            'product' => new ProductsResource($product),
            'variationOptions' => request('options', [])
        ]);
    }
}
