<?php

namespace App\Services;

use Binance\API;

class BinanceService
{
    protected $api;

    public function __construct()
    {
        $this->api = new API(
            config('binance.api_key'),
            config('binance.secret')
        );
    }

    public function getBTCPrice()
    {
        $ticker = $this->api->prices(); // Obtén todos los precios
        return $ticker['BTCUSDT'] ?? null; // Devuelve el precio de BTC/USDT
    }

    /**
     * Obtener el saldo total de una moneda específica.
     *
     * @param string $currency Código de la moneda (ej. BTC, USDT).
     * @return array|null
     */
    public function getBalance($currency = null)
    {
        try {
            $balances = $this->api->balances();

            if ($currency) {
                return $balances[$currency] ?? null;
            }

            return $balances; // Devuelve todas las monedas si no se especifica ninguna
        } catch (\Exception $e) {
            // Devolver detalles del error
            return ['error' => $e->getMessage()];
            \Log::error('Error al obtener balances: ' . $e->getMessage());
        }
    }
}
