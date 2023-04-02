<?php

namespace app\Bin;

class BinProvider
{
    private const EU_COUNTRY_CODES = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK'
    ];

    public function __construct(private BinProviderInterface $cardResolver)
    {

    }

    public function isEu(string $bin): bool
    {
        $code = $this->cardResolver->getCountryCode($bin);

        return in_array($code, self::EU_COUNTRY_CODES);
    }
}