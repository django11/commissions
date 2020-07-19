<?php

namespace django11\TransactionCommission\Tests;

use django11\TransactionCommission\Providers\BinLookupProvider;
use django11\TransactionCommission\Providers\CurrencyRateProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class ProviderTests
 * @package django11\TransactionCommission\Tests
 */
class ProviderTest  extends TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \django11\TransactionCommission\Exceptions\CurrencyRateApiException
     */
    public function testCurrencyRateApi(): void
    {
        $currencyRate = (new CurrencyRateProvider())->getRate('USD');

        $this->assertInternalType('float', $currencyRate);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \django11\TransactionCommission\Exceptions\BinApiException
     */
    public function testBinLookupApi(): void
    {
        $countryAlpha2 = (new BinLookupProvider())->getCountryAlpha2ByBin('45717360');

        $this->assertInternalType('string', $countryAlpha2);
        $this->assertEquals(2, strlen($countryAlpha2));
    }
}
