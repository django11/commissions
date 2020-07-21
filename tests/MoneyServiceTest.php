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
    /**
     * @var MoneyService
     */
    private $moneyService;

    public function setUp(): void
    {
        $this->moneyService = MoneyService::init();
    }

    public function testParseMoney(): void
    {
        $parsedToMoney = $this->moneyService->parse('11.11', 'USD');
        $this->assertInstanceOf(Money::class, $parsedToMoney);
    }

    public function testMoneyFormat(): void
    {
        $formattedMoney = $this->moneyService->format(new Money(1111, new Currency('USD')));
        $this->assertEquals('11.11', $formattedMoney);
    }
}
