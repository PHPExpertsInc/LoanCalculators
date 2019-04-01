<?php declare(strict_types=1);

namespace PHPExperts\LoanCalculators\LoanLogic;

use PHPExperts\LoanCalculators\DTOs\InstallmentDTO;

/**
 * @package PHPExperts\LoanCalculators\LoanLogic
 * @internal
 */
class FairCompounding extends CompoundingInterest implements LoanLogic
{
    public function calculateInterestPayment(float $principal, float $interestRate, int $installmentUnits): float
    {
        $P = $principal;
        $r = $interestRate;
        $n = $installmentUnits;

        // Skip for negative or zero amounts.
        if ($principal < 0.01) {
            return 0.00;
        }

        $Ii = $P * ($r / $n);

        return round($Ii, 2);
    }

    public function generateLoanBillingStatements(
        float $principal,
        float $totalOwed,
        int $numberOfInstallments,
        InstallmentDTO $installmentDTO,
        float $fees = 0.0): array
    {
        $amountPaid = 0.0;
        $feesPaid = 0.0;
        $installmentUnits = $installmentDTO->units;
        $feeInstallment = round($fees / $numberOfInstallments, 2);

        $statements = [];
        $iT = $totalOwed / $numberOfInstallments;
        for ($a = 1; $a <= $numberOfInstallments + 1; ++$a) {
            $interestRate = $this->calculateInterestRate($principal, $totalOwed, $installmentUnits, $numberOfInstallments * $installmentUnits);
            $iI = $this->calculateInterestPayment($principal, $interestRate, 7);
            $iP = round($iT - $iI, 2);

            $statements[] = compact('principal', 'iT', 'iI', 'iP', 'feesPaid', 'amountPaid');

            $feesPaid += $feeInstallment;
            $amountPaid += $iI + $iP + $feeInstallment;
            $principal = max(0, $principal - ($amountPaid - $feeInstallment));
        }

        return $statements;
    }
}
