<?php

namespace app\Bin\Providers;

use app\Bin\BinProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use RuntimeException;

class BinList implements BinProviderInterface
{
    private const URL = 'https://lookup.binlist.net/';

    public function __construct(private Client $client)
    {
    }

    public function getCountryCode(string $bin): string
    {
        try {
            $response = $this->client->request('GET', self::URL . $bin);
            if ($response->getStatusCode() !== 200) {
                throw new RuntimeException('Invalid response from Binlist API');
            }

            $results = json_decode($response->getBody()->getContents());

            return $results->country->alpha2;
        } catch (RequestException|GuzzleException $e) {
            throw new RuntimeException('Error communicating with Binlist API: ' . $e->getMessage());
        }
    }
}