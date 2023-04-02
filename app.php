<?php

use app\Bin\BinProvider;
use app\Bin\Providers\BinList;
use app\Exchange\Exchanger;
use app\Exchange\Providers\ExchangeRatesAPI;
use app\FeeCalculator;
use app\File\InputFileParser;
use app\Main;
use GuzzleHttp\Client;

require_once(__DIR__ . '/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new Client();
$feeCalculator = new FeeCalculator(cardResolver: new BinProvider(cardResolver: new BinList($client)), exchanger: new Exchanger(new ExchangeRatesAPI($client, $_ENV['EXCHANGE_RATE_API_KEY'])));

$main = new Main($feeCalculator, new InputFileParser());

if (!isset($argv[1])) {
    echo "Please provide a filename as the first argument\n";
    exit(1);
}

$main->run($argv[1]);