<?php declare(strict_types=1);

namespace django11\TransactionCommission;

use django11\TransactionCommission\Contracts\BinProviderInterface;
use django11\TransactionCommission\Contracts\CurrencyRateProviderInterface;
use django11\TransactionCommission\Services\MoneyService;
use Money\Money;

/**
 * Class CommissionCalculator
 * @package django11\TransactionCommission
 */
class CommissionCalculator
{
    /**
     * @var CurrencyRateProviderInterface
     */
    private $currencyRateProvider;

    /**
     * @var BinProviderInterface
     */
    private $binProvider;

    /**
     * CommissionCalculator constructor.
     *
     * @param CurrencyRateProviderInterface $currencyRateProvider
     * @param BinProviderInterface $binProvider
     */
    public function __construct(
        CurrencyRateProviderInterface $currencyRateProvider,
        BinProviderInterface $binProvider)
    {
        $this->currencyRateProvider = $currencyRateProvider;
        $this->binProvider = $binProvider;
    }

    /**
     * @param array $transactionData
     *
     * @return Money
     */
    public function calculate(array $transactionData): Money
    {
        $moneyService = MoneyService::init();

        $countryAlpha2 = $this->binProvider->getCountryAlpha2ByBin($transactionData['bin']);
        $amount = $moneyService->parse($transactionData['amount'], $transactionData['currency']);

        if ($amount->getCurrency()->getCode() !== Config::BASE_CURRENCY_ISO) {
            $rate = $this->currencyRateProvider->getRate($amount->getCurrency()->getCode());
            $amount = $amount->divide($rate);
        }

        $rate = $this->isEuCountry($countryAlpha2) ? Config::EU_RATE : Config::NON_EU_RATE;

        return $amount->multiply($rate);
    }

    /**
     * @param string $countryAlpha2
     *
     * @return bool
     */
    private function isEuCountry(string $countryAlpha2): bool
    {
        return in_array($countryAlpha2, Config::EU_COUNTRIES_ISO_CODES, true);
    }
}
