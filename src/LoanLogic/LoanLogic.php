<?php declare(strict_types=1);

/**
 * This file is part of LoanCalculators, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *  GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *  https://www.phpexperts.pro/
 *  https://github.com/phpexpertsinc/LoanCalculators
 *
 * This file is licensed under the Creative Commons No-Derivatives v4.0.
 */

namespace PHPExperts\LoanCalculators\LoanLogic;

/**
 * @package PHPExperts\LoanCalculators\LoanLogic
 * @internal
 */
interface LoanLogic
{
    public function calculateTotalOwed(float $principal, float $interestRate, float $installments, int $installmentUnits): float;

    public function calculateInterestRate($principal, $totalOwed, int $daysToPay): float;

    public function calculateInterestPayment($principal, $totalOwed, float $installments, int $installmentUnits): float;
}
