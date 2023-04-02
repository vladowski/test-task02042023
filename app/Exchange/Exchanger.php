<?php

namespace app\Exchange;

class Exchanger
{
    public function __construct(private ExchangerInterface $exchanger)
    {

    }

    public function exchange(string $from, string $to, float $amount): float
    {
        if ($from === $to) {
            return $amount;
        }

        $rate = $this->exchanger->getRate($from, $to);

        if ($rate === 0.0) {
            return $amount;
        }

        return $amount / $rate;
    }
}