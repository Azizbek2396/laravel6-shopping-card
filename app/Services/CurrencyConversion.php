<?php


namespace App\Services;


use App\Models\Currency;

class CurrencyConversion
{
    protected static $container;
    public static function loadContainer()
    {

    }
    public static function convert($sum, $originCurrencyCode = 'RUB', $targetCurrencyCode = null)
    {
        self::loadContainer();
        $originCurrency = Currency::byCode($originCurrencyCode)->first();
        if (is_null($targetCurrencyCode)) {
            $targetCurrencyCode = session('currency', 'RUB');
        }
        $targetCurrency = Currency::byCode($targetCurrencyCode)->first();

        return $sum * $originCurrency->rate / $targetCurrency->rate;
    }

    public static function getCurrencySymbol()
    {
        self::loadContainer();
        $currency = Currency::byCode(session('currency', 'RUB'))->first();
        return $currency->symbol;
    }

}
