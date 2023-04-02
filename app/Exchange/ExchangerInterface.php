<?php

namespace app\Exchange;

interface ExchangerInterface
{
    public function getRate(string $from, string $to): float;
}