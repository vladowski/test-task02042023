<?php

namespace tests;

use app\Bin\BinProvider;
use app\Bin\BinProviderInterface;
use PHPUnit\Framework\TestCase;

class BinProviderTest extends TestCase
{

    public function testIsEuReturnsTrueForEUCode()
    {
        $mock = $this->createMock(BinProviderInterface::class);
        $mock->expects($this->once())
            ->method('getCountryCode')
            ->with('123456')
            ->willReturn('AT');

        $binProvider = new BinProvider($mock);

        $this->assertTrue($binProvider->isEu('123456'));
    }

    public function testIsEuReturnsFalseForNonEUCode()
    {
        $mock = $this->createMock(BinProviderInterface::class);
        $mock->expects($this->once())
            ->method('getCountryCode')
            ->with('123456')
            ->willReturn('US');

        $binProvider = new BinProvider($mock);

        $this->assertFalse($binProvider->isEu('123456'));
    }
}
