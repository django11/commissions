<?php

namespace django11\TransactionCommission\Contracts;

/**
 * Interface BinProviderInterface
 * @package django11\TransactionCommission\Contracts
 */
interface BinProviderInterface
{
    /**
     * @param string $bin
     *
     * @return string
     */
    public function getCountryAlpha2ByBin(string $bin): string;
}
