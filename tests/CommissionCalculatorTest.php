<?php declare(strict_types=1);

namespace django11\TransactionCommission\Tests;

use django11\TransactionCommission\CommissionCalculator;
use django11\TransactionCommission\Contracts\BinProviderInterface;
use django11\TransactionCommission\Contracts\CurrencyRateProviderInterface;
use django11\TransactionCommission\Services\MoneyService;
use PHPUnit\Framework\TestCase;

/**
 * Class CommissionCalculatorTest
 * @package django11\TransactionCommission\Tests
 */
class CommissionCalculatorTest extends TestCase
{
    /**
     * @var MoneyService
     */
    private $moneyService;

    public function setUp(): void
    {
        $this->moneyService = MoneyService::init();
    }

    /**
     * @dataProvider calculationProvider
     *
     * @param array $transactionData
     * @param float $expectedResult
     */
    public function testCalculate(array $transactionData, float $expectedResult): void
    {
        $commissionCalculator = new CommissionCalculator($this->getCurrencyRateProviderMock(), $this->getBinLookupProviderMock());
        $result = $commissionCalculator->calculate($transactionData);
        $this->assertEquals($expectedResult, $this->moneyService->format($result));
    }

    /**
     * @return CurrencyRateProviderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getCurrencyRateProviderMock(): CurrencyRateProviderInterface
    {
        $currencyRateProviderMock = $this->createMock(CurrencyRateProviderInterface::class);
        $currencyRateProviderMock->method('getRate')
            ->willReturnCallback([$this, 'getRate']);

        return $currencyRateProviderMock;
    }

    /**
     * @return BinProviderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getBinLookupProviderMock(): BinProviderInterface
    {
        $binLookupProviderMock = $this->createMock(BinProviderInterface::class);
        $binLookupProviderMock->method('getCountryAlpha2ByBin')
            ->willReturnMap([
                ['45717360', 'DK'],
                ['516793', 'LT'],
                ['45417360', 'JP'],
                ['4745030', 'GB'],
            ]);

        return $binLookupProviderMock;
    }

    /**
     * @return float
     * @throws \JsonException
     */
    public function getRate(): float
    {
        $currencyIsoCode = func_get_args()[0];

        $result = json_decode('{"rates":{"CAD":1.551,"HKD":8.8617,"ISK":160.2,"PHP":56.511,"DKK":7.4453,"HUF":353.72,"CZK":26.682,"AUD":1.636,"RON":4.8422,"SEK":10.333,"IDR":16793.45,"INR":85.67,"BRL":6.0839,"RUB":81.8409,"HRK":7.538,"JPY":122.53,"THB":36.238,"CHF":1.0753,"SGD":1.5887,"PLN":4.4827,"BGN":1.9558,"TRY":7.8413,"CNY":7.9975,"NOK":10.5995,"NZD":1.7463,"ZAR":19.0496,"USD":1.1428,"MXN":25.6132,"ILS":3.9253,"GBP":0.91078,"KRW":1376.55,"MYR":4.8723},"base":"EUR","date":"2020-07-17"}', true, 512, JSON_THROW_ON_ERROR);

        return $result['rates'][$currencyIsoCode];
    }

    /**
     * @return array[]
     */
    public function calculationProvider(): array
    {
        return [
            [
                'data' => ['bin' => '45717360','amount' => '100.00', 'currency' => 'EUR'],
                'result' => 1.00
            ],
            [
                'data' => ['bin' => '516793', 'amount' => '50.00', 'currency' => 'USD'],
                'result' => 0.44
            ],
            [
                'data' => ['bin' => '45417360', 'amount' => '10000.00', 'currency' => 'JPY'],
                'result' => 2.00
            ],
            [
                'data' => ['bin' => '4745030', 'amount' => '2000.00', 'currency' => 'GBP'],
                'result' => 43.92
            ]
        ];
    }
}
