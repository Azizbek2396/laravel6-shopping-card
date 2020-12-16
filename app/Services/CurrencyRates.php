<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;

class CurrencyRates
{
    public static function getRates()
    {
        $baseCurrency = CurrencyConversion::getBaseCurrency();

        $url = 'https://api.exchangeratesapi.io/latest' . '?base=' . $baseCurrency->code;

        $client = new Client();

        $response = $client->request('GET', $url);

        if($response->getStatusCode() !== 200) {
            throw new Exception('There is a problem with currency rate service');
        }

        $rates = json_decode($response->getBody()->getContents(), true)['rates'];
//        dd($rates);
        foreach (CurrencyConversion::getCurrencies() as $currency){
            if (!$currency->isMain()){
//                dd(isset($rates[$currency->code]));
                if (!isset($rates[$currency->code])){
//                    dd($currency->code);
                    throw new Exception('There is a problem with currency ' . $currency->code);
                } else {
                    $currency->update(['rate' => $rates[$currency->code]]);
                }
            }
        }
    }
}
