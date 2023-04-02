<?php

namespace app\Exchange\Providers;

use app\Exchange\ExchangerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use RuntimeException;

class ExchangeRatesAPI implements ExchangerInterface
{
    private const URL = 'https://api.apilayer.com/exchangerates_data/latest';

    public function __construct(private Client $client, private string $apiKey)
    {

    }

    public function getRate(string $from, string $to): float
    {
        try {
            $response = $this->client->request('GET', self::URL, [
                'headers' => [
                    'apiKey' => $this->apiKey
                ]
            ]);


            if ($response->getStatusCode() !== 200) {
                throw new RuntimeException('Invalid response from Exchange Rates API');
            }

            $data = json_decode($response->getBody()->getContents(), true);

            if (!$data || !array_key_exists('rates', $data)) {
                throw new RuntimeException('Invalid response from Exchange Rates API');
            }

            if (!array_key_exists($to, $data['rates'])) {

                print_r($data);
                throw new RuntimeException(sprintf('Currency rate for "%s" was not received', $to));
            }

            return (float)$data['rates'][$to];
        } catch (RequestException|GuzzleException $e) {
            throw new RuntimeException('Error communicating with Exchange Rates API: ' . $e->getMessage());
        }
    }
}