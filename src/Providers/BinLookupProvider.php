<?php declare(strict_types=1);

namespace django11\TransactionCommission\Providers;

use django11\TransactionCommission\Contracts\BinProviderInterface;
use django11\TransactionCommission\Exceptions\BinApiException;
use GuzzleHttp\Client;

/**
 * Class BinLookup
 * @package django11\TransactionCommission\Providers
 */
class BinLookupProvider implements BinProviderInterface
{
    public const API_URL = 'https://lookup.binlist.net/';

    /**
     * @param string $bin
     *
     * @return string
     * @throws BinApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCountryAlpha2ByBin(string $bin): string
    {
        $response = $this->getClient()->request('GET', $bin);

        if ($response->getStatusCode() !== 200) {
            throw new BinApiException('Something went wrong getting BIN data.');
        }

        $result = json_decode($response->getBody()->getContents());

        return $result->country->alpha2;
    }

    /**
     * @return Client
     */
    private function getClient(): Client
    {
        return new Client(['base_uri' => self::API_URL]);
    }
}
