<?php

namespace django11\TransactionCommission\Contracts;

/**
 * Interface CurrencyRateProviderInterface
 * @package django11\TransactionCommission\Contracts
 */
interface CurrencyRateProviderInterface
{
    /**
     * @param string $currencyIsoCode
     *
     * @return float
     */
    public function getRate(string $currencyIsoCode): float;
}
