<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductsFilterRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    public function index (ProductsFilterRequest $request) {
//        dd($request->ip());
//        dd(get_class_methods($request));
//        Log::channel('single')->debug($request->ip());

//        \Debugbar::info($request);
        $productsQuery = Product::with('category');

        if ($request->filled('price_from')) {
            $productsQuery->where('price', '>=', $request->price_from);
        }

        if ($request->filled('price_to')) {
            $productsQuery->where('price', '<=', $request->price_to);
        }

        foreach (['hit', 'new', 'recommend'] as $field) {
            if ($request->has($field)){
                $productsQuery->$field();
            }
        }

        $products = $productsQuery->paginate(6)->withPath("?" . $request->getQueryString());

        return view("index", compact('products'));
    }

    public function categories() {
        $categories = Category::get();

        return view("categories", compact('categories'));
    }

    public function category($code) {
        $category = Category::where('code', $code)->first();dd($category);
        return view('category', compact('category'));
    }

    public function product($category, $productCode) {

        $product = Product::withTrashed()->byCode($productCode)->first();
        return view("product", compact('product'));
    }
}
