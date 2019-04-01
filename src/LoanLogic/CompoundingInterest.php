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

use PHPExperts\LoanCalculators\DTOs\InstallmentDTO;

/**
 * @package PHPExperts\LoanCalculators\LoanLogic
 * @internal
 */
class CompoundingInterest implements LoanLogic
{
    public function calculateTotalOwed(float $principal, float $interestRate, float $installments, int $installmentUnits): float
    {
        // A = P(1 + r/n)nt
        $A = null;
        $P = $principal;
        $r = $interestRate;
        $n = $installments;
        $nt = $installments * $installmentUnits;

        $A = $P * (1 + ($r / $n)) ** ($nt);

        dd(
            compact('A', 'P', 'r', 'n', 'nt', 'A')
        );

        return $A;
    }

    public function calculateInterestRate($principal, $totalOwed, int $daysToPay): float
    {
        $A = $totalOwed;
        $P = $principal;
        $r = null;
        $nt = $daysToPay;

        // Skip for negative or zero amounts.
        if ($principal < 0.01) {
            return 0.00;
        }

        $r = ($A / $P) / $nt;

        return $r;
    }

    public function calculatePayment(float $principal, float $interestRate, int $installmentUnits): float
    {
        /*************************************************************
         *              INTEREST * ((1 + INTEREST) ^ TOTALPAYMENTS)
         * PMT = LOAN * -------------------------------------------
         *                  ((1 + INTEREST) ^ TOTALPAYMENTS) - 1
         ************************************************************/

        $P = $principal;
        $r = $interestRate;
        $n = $installmentUnits;

        // Skip for negative or zero amounts.
        if ($principal < 0.01) {
            return 0.00;
        }

        $top    = $r * (1 + $r)**2;
        $bottom = ((1 + $r)**2) - 1;
        $pmt    = $P * ($top / $bottom);

        return $pmt;
    }

    public function calculateInterestPayment($principal, $totalOwed, float $installments, int $installmentUnits): float
    {
        $A = $totalOwed;
        $P = $principal;
        $r = null;
        $n = $installmentUnits;
        $nt = $installments * $installmentUnits;

        // Skip for negative or zero amounts.
        if ($principal < 0.01) {
            return 0.00;
        }

        $r = $n * (($A / $P)) ** (1 / (($nt))) - $n;

        return $r;
    }

    public function calculateAPR(float $principal, float $totalOwed, int $daysToPay): float
    {
        // Equation: $75 ÷ $375 = 0.2 x 365 days = $73 ÷ 14 days = 5.21 x 100 = 521%.
        $interestPaid = $totalOwed - $principal;
        $apr = ($interestPaid / $principal) / $daysToPay * 365;

        return $apr;
    }

    public function calculateInterestRateFromAPR(float $apr): float
    {
        return $apr / 365;
    }

    public function generateLoanBillingStatements(
        float $principal,
        float $interestRate,
        int $numberOfInstallments,
        InstallmentDTO $installmentDTO,
        float $fees = 0.0): array
    {
        $amountPaid = 0.0;
        $feesPaid = 0.0;
        $installmentUnits = $installmentDTO->units;
        $feeInstallment = $fees > 0 ? round($fees / $numberOfInstallments, 2) : 0;

        $totalOwed = $this->calculateTotalOwed($principal, $interestRate, $numberOfInstallments, $installmentUnits);
        dd($totalOwed);

        $iT = round($this->calculatePayment($principal, 1, 2), 2);
        for ($a = 1; $a <= $numberOfInstallments + 1; ++$a) {
            $iI = round($this->calculateInterestPayment($principal, $totalOwed, $installmentUnits), 2);
            $iP = round($iT - $iI, 2);

            $statements[] = compact('principal', 'iT', 'iI', 'iP', 'feesPaid', 'amountPaid');

            $feesPaid += $feeInstallment;
            $amountPaid += $iI + $iP + $feeInstallment;
            $principal = max(0, $principal - $iP);
        }

        return $statements;
    }
}















