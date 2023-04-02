<?php

namespace app;

use app\File\InputFileParser;
use Exception;

class Main
{
    public function __construct(private FeeCalculator $feeCalculator, private InputFileParser $fileParser)
    {

    }

    public function run(string $filename)
    {
        try {
            foreach ($this->fileParser->getRows($filename) as $fileRow) {
                $fee = $this->feeCalculator->calculate(
                    $fileRow->getBin(),
                    $fileRow->getAmount(),
                    $fileRow->getCurrency()
                );

                printf("%.2F" . PHP_EOL, $fee);
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }
}