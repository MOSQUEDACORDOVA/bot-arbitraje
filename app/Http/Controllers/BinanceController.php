<?php

namespace App\Http\Controllers;

use App\Services\BinanceService;
use Illuminate\Http\Request;

class BinanceController extends Controller
{
    protected $binanceService;

    public function __construct(BinanceService $binanceService)
    {
        $this->binanceService = $binanceService;
    }

    public function getBTCPrice()
    {
        $price = $this->binanceService->getBTCPrice();

        if ($price) {
            return response()->json([
                'pair' => 'BTC/USDT',
                'price' => $price
            ]);
        }

        return response()->json(['error' => 'No se pudo obtener el precio'], 500);
    }

    /**
     * Obtener el saldo de una moneda específica o todas las monedas.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBalance(Request $request)
    {
        $currency = $request->input('currency'); // Moneda opcional (BTC, USDT, etc.)
        $balance = $this->binanceService->getBalance($currency);

        if ($balance) {
            return response()->json([
                'currency' => $currency ?? 'all',
                'balance' => $balance
            ]);
        }

        return response()->json(['error' => 'No se pudo obtener el saldo'], 500);
    }
}
