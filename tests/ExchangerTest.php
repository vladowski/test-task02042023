<?php

namespace tests;

use app\Exchange\Exchanger;
use app\Exchange\ExchangerInterface;
use PHPUnit\Framework\TestCase;

class ExchangerTest extends TestCase
{
    private ExchangerInterface $exchangerMock;
    private Exchanger $exchanger;

    protected function setUp(): void
    {
        $this->exchangerMock = $this->createMock(ExchangerInterface::class);
        $this->exchanger = new Exchanger($this->exchangerMock);
    }

    public function testExchangeWithSameCurrency(): void
    {
        $from = 'USD';
        $to = 'USD';
        $amount = 10.0;

        $result = $this->exchanger->exchange($from, $to, $amount);

        $this->assertSame($amount, $result);
        $this->exchangerMock->expects($this->never())->method('getRate');
    }

    public function testExchangeWithZeroRate(): void
    {
        $from = 'USD';
        $to = 'EUR';
        $amount = 10.0;

        $this->exchangerMock
            ->expects($this->once())
            ->method('getRate')
            ->with($from, $to)
            ->willReturn(0.0);

        $result = $this->exchanger->exchange($from, $to, $amount);

        $this->assertSame($amount, $result);
    }

    public function testExchangeWithNonZeroRate(): void
    {
        $from = 'USD';
        $to = 'EUR';
        $amount = 10.0;
        $rate = 0.85;

        $this->exchangerMock = $this->createMock(ExchangerInterface::class);

        $this->exchangerMock
            ->expects($this->once())
            ->method('getRate')
            ->with($from, $to)
            ->willReturn($rate);

        $this->exchanger = new Exchanger($this->exchangerMock);

        $expectedResult = $amount / $rate;

        $result = $this->exchanger->exchange($from, $to, $amount);

        $this->assertEquals($expectedResult, $result);
    }
}
