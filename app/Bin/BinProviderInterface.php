<?php

namespace app\Bin;

interface BinProviderInterface
{
    public function getCountryCode(string $bin): string;
}