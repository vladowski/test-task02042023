<?php

namespace tests;

use app\Main;
use app\FeeCalculator;
use app\File\InputFileParser;
use app\File\FileRow;
use ArrayIterator;
use Exception;
use PHPUnit\Framework\TestCase;

class MainTest extends TestCase
{
    public function testRunWithValidFile(): void
    {
        $feeCalculatorMock = $this->createMock(FeeCalculator::class);

        $feeCalculatorMock
            ->expects($this->once())
            ->method('calculate')
            ->with('123456', 100.0, 'EUR')
            ->willReturn(0.5);

        $inputFileParserMock = $this->createMock(InputFileParser::class);

        $inputFileParserMock->expects($this->once())
            ->method('getRows')
            ->willReturn(new ArrayIterator([
                new FileRow('123456', 100, 'EUR'),
            ]));

        $main = new Main($feeCalculatorMock, $inputFileParserMock);

        ob_start();
        $main->run('valid_file.txt');
        $output = ob_get_clean();

        $this->assertEquals("0.50\n", $output);
    }

    public function testRunWithInvalidFile(): void
    {
        $feeCalculatorMock = $this->createMock(FeeCalculator::class);
        $inputFileParserMock = $this->createMock(InputFileParser::class);

        $inputFileParserMock->expects($this->once())
            ->method('getRows')
            ->willThrowException(new Exception('Error parsing file'));

        $main = new Main($feeCalculatorMock, $inputFileParserMock);

        ob_start();
        $main->run('invalid_file.txt');
        $output = ob_get_clean();

        $this->assertEquals("Error parsing file", $output);
    }
}
