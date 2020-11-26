<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    public function basket() {

        $orderId = session('orderId');
        if (!is_null($orderId)) {
            $order = Order::findOrFail($orderId);
        }

//        dump($order);

        return view('basket', compact('order'));
    }

    public function basketConfirm(Request $request)
    {
        $orderId = session('orderId');

        if (is_null($orderId)) {
            return redirect()->route('index');
        }
        $order = Order::find($orderId);
        $success = $order->saveOrder($request->name, $request->phone);

        if ($success) {
            session()->flash('success', 'Ваш заказ принят в обработку!');
        } else {
            session()->flash('warning', 'Случилось ошибка!');
        }
//        dd($request->all());
        return redirect()->route('index');
    }

    public function basketPlace() {
        $orderId = session('orderId');

        if (is_null($orderId)) {
            return redirect()->route('index');
        }

        $order = Order::find($orderId);
//        dd($order);
        return view('order', compact('order'));
    }

    public function basketAdd($productId)
    {
        $orderId = session('orderId');

//        dd($orderId);
        if (is_null($orderId)) {

            $order = Order::create();

            session(['orderId' => $order->id]);
        }else {
            $order = Order::find($orderId);
        }

        if ($order->products->contains($productId)){
            $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
            $pivotRow->count++;
            $pivotRow->update();
        } else {
            $order->products()->attach($productId);
        }

        $product = Product::find($productId);

        session()->flash('success', 'Добавлен товар ' . $product->name);

        return redirect()->route('basket');

//        return view('basket', compact('order'));
    }

    public function basketRemove($productId)
    {
        $orderId = session('orderId');
        if (is_null($orderId)){
            return redirect()->route('basket');
        }
        $order =  Order::find($orderId);
        if ($order->products->contains($productId)){
            $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
            if ($pivotRow->count < 2) {
                $order->products()->detach($productId);
            }else {
                $pivotRow->count--;
            }
            $pivotRow->update();
//            dd($pivotRow);
        }

        $product = Product::find($productId);
        session()->flash('warning', 'Удалён товар ' . $product->name);

        return redirect()->route('basket');
    }
}