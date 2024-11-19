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

    /**
     * Realizar una orden de compra para intercambiar USDT por USDC.
     *
     * @param string $quantity Cantidad de USDT a usar.
     * @return array|null
     */
    public function buyUSDCWithUSDT($quantity)
    {
        try {
            // Crear la orden de compra
            $order = $this->api->marketBuy('USDCUSDT', $quantity);
            
            // Verificar si la orden fue exitosa
            if (isset($order['status']) && $order['status'] == 'FILLED') {
                return [
                    'status' => 'success',
                    'message' => 'Compra exitosa',
                    'order' => $order
                ];
            }

            return [
                'status' => 'error',
                'message' => 'La compra no se completó correctamente',
                'order' => $order
            ];

        } catch (\Exception $e) {
            // Manejo de errores
            return ['error' => $e->getMessage()];
        }
    }
}
