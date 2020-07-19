<?php

namespace django11\TransactionCommission\Tests;

use django11\TransactionCommission\Services\MoneyService;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

/**
 * Class MoneyServiceTests
 * @package django11\TransactionCommission\Tests
 */
class MoneyServiceTest extends TestCase
{
    public function testParseMoney()
    {
        $moneyService = MoneyService::init();
        $parsedToMoney = $moneyService->parse('11.11', 'USD');
        $this->assertInstanceOf(Money::class, $parsedToMoney);
    }


    public function testMoneyFormat()
    {
        $moneyService = MoneyService::init();
        $formattedMoney = $moneyService->format(new Money(1111, new Currency('USD')));
        $this->assertEquals('11.11', $formattedMoney);
    }
}
