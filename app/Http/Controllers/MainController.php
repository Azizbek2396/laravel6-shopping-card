<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductsFilterRequest;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Sku;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

//use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    public function index (ProductsFilterRequest $request) {
//        dd($request->ip());
//        dd(get_class_methods($request));
//        Log::channel('single')->debug($request->ip());

//        \Debugbar::info($request);

        $skusQuery = Sku::query();

//        \App\Services\CurrencyRates::getRates();
//        $productsQuery = Product::with('category');
//
//        if ($request->filled('price_from')) {
//            $productsQuery->where('price', '>=', $request->price_from);
//        }
//
//        if ($request->filled('price_to')) {
//            $productsQuery->where('price', '<=', $request->price_to);
//        }
//
//        foreach (['hit', 'new', 'recommend'] as $field) {
//            if ($request->has($field)){
//                $productsQuery->$field();
//            }
//        }
//
//        $products = $productsQuery->paginate(6)->withPath("?" . $request->getQueryString());

        $skus = $skusQuery->paginate(6);
        return view("index", compact('skus'));
    }

    public function categories(){

        return view("categories");
    }

    public function category($code) {
        $category = Category::where('code', $code)->first();
        return view('category', compact('category'));
    }

    public function sku($categoryCode, $productCode, Sku $skus)
    {
        if ($skus->product->code != $productCode) {
            abort(404, 'product not found.');
        }

        if ($skus->product->category->code != $categoryCode) {
            abort(404, 'Category not found.');
        }

        return view("product", compact('skus'));
    }

    public function subscribe(SubscriptionRequest $request, Sku $skus)
    {
        Subscription::create([
            'email' => $request->email,
            'sku_id' => $skus->id,
        ]);
        return redirect()->back()->with('success', __('product.we_will_update'));
    }

    public function changeLocale($locale)
    {
        $availableLocales = ['ru', 'en'];
        if (!in_array($locale, $availableLocales)){
            $locale = config('app.locale');
        }
        session(['locale' => $locale]);
        App::setLocale($locale);
        return redirect()->back();
    }

    public function changeCurrency($currencyCode)
    {
        $currency = Currency::byCode($currencyCode)->firstOrFail();
        session(['currency' => $currency->code]);

        return redirect()->back();
    }
}
