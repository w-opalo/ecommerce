<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductListResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;

class ProductController extends Controller
{
    public function home()
    {
        $products = Product::query()
            ->published()
            ->paginate(12);
        return Inertia::render('welcome', [
            'products' => ProductListResource::collection($products),
        ]);
    }

    public function show(Product $product) {}
}
