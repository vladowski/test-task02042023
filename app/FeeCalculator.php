<?php

namespace app;

use app\Bin\BinProvider;
use app\Exchange\Exchanger;

class FeeCalculator
{
    private const EU_FEE = 0.01;
    private const NON_EU_FEE = 0.02;
    private const BASE_CURRENCY = 'EUR';

    public function __construct(private BinProvider $cardResolver, private Exchanger $exchanger)
    {

    }

    public function calculate(string $bin, float $amount, string $currency): float
    {
        $amountFixed = $this->exchanger->exchange(self::BASE_CURRENCY, $currency, $amount);
        $fee = $this->cardResolver->isEu($bin) ? self::EU_FEE : self::NON_EU_FEE;

        return $amountFixed * $fee;
    }
}