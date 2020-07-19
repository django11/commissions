<?php declare(strict_types=1);

use django11\TransactionCommission\CommissionCalculator;
use django11\TransactionCommission\Providers\CurrencyRateProvider;
use django11\TransactionCommission\Providers\BinLookupProvider;
use django11\TransactionCommission\Services\MoneyService;

require_once __DIR__ . '/vendor/autoload.php';

$commissionCalculator = new CommissionCalculator(new CurrencyRateProvider(), new BinLookupProvider());
$moneyService = MoneyService::init();

foreach (explode("\n", file_get_contents($argv[1])) as $row) {
    if (empty($row)) {
        break;
    }

    try {
        $commission = $commissionCalculator->calculate(json_decode($row, true, 512, JSON_THROW_ON_ERROR));
        echo $moneyService->format($commission);
        echo "\n";
    } catch (Exception $exception) {
        echo $exception->getMessage();
        echo "\n";
    }
}
