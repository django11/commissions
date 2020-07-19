<?php declare(strict_types=1);

namespace django11\TransactionCommission\Providers;

use django11\TransactionCommission\Contracts\CurrencyRateProviderInterface;
use django11\TransactionCommission\Exceptions\CurrencyRateApiException;
use GuzzleHttp\Client;

/**
 * Class CurrencyRate
 * @package django11\TransactionCommission\Providers
 */
class CurrencyRateProvider implements CurrencyRateProviderInterface
{
    public const API_URL = 'https://api.exchangeratesapi.io/latest';

    /**
     * @param string $currencyIsoCode
     *
     * @return float
     * @throws CurrencyRateApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRate(string $currencyIsoCode): float
    {
        $response = $this->getClient()->request('GET');

        if ($response->getStatusCode() !== 200) {
            throw new CurrencyRateApiException('Currency rate api error.');
        }

        $result = json_decode($response->getBody()->getContents(), true);

        return $result['rates'][$currencyIsoCode];
    }

    /**
     * @return Client
     */
    private function getClient(): Client
    {
        return new Client(['base_uri' => self::API_URL]);
    }
}
