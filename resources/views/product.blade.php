@extends('layouts.master')

@section('title', 'Товар')

@section('content')
    <h1>iPhone X 64GB</h1>
{{--        <h1>{{ $product }}</h1>--}}
    <p>Цена: <b>71990 руб.</b></p>
    <img src="/img/products/iphone_x.jpg">
    <p>Отличный продвинутый телефон с памятью на 64 gb</p>
    <a class="btn btn-success" href="http://laravel-diplom-1.rdavydov.ru/basket/1/add">Добавить в корзину</a>
@endsection