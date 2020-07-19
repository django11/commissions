<?php declare(strict_types=1);

namespace django11\TransactionCommission\Services;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

/**
 * Class MoneyService
 * @package django11\TransactionCommission\Services
 */
class MoneyService
{
    /** @var self */
    private static $instance;

    /**
     * @var DecimalMoneyParser
     */
    private $decimalMoneyParser;

    /**
     * @var DecimalMoneyFormatter
     */
    private $decimalMoneyFormatter;

    /**
     * MoneyService constructor.
     *
     * @param DecimalMoneyParser $decimalMoneyParser
     * @param DecimalMoneyFormatter $decimalMoneyFormatter
     */
    public function __construct(DecimalMoneyParser $decimalMoneyParser, DecimalMoneyFormatter $decimalMoneyFormatter)
    {
        $this->decimalMoneyParser = $decimalMoneyParser;
        $this->decimalMoneyFormatter = $decimalMoneyFormatter;
    }

    /**
     * @return MoneyService
     */
    public static function init(): MoneyService
    {
        if (!self::$instance) {
            $currencies = new ISOCurrencies();
            self::$instance = new self(new DecimalMoneyParser($currencies), new DecimalMoneyFormatter($currencies));
        }

        return self::$instance;
    }

    /**
     * @param string $amount
     * @param string $currency
     *
     * @return Money
     */
    public function parse(string $amount, string $currency): Money
    {
        return $this->decimalMoneyParser->parse($amount, $currency);
    }

    /**
     * @param Money $amount
     *
     * @return string
     */
    public function format(Money $amount): string
    {
        return $this->decimalMoneyFormatter->format($amount);
    }
}
