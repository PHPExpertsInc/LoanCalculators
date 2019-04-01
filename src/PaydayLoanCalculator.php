<?php declare(strict_types=1);

namespace PHPExperts\LoanCalculators;

use PHPExperts\LoanCalculators\DTOs\InstallmentDTO;
use PHPExperts\LoanCalculators\LoanLogic\CompoundingInterest as CompoundingInterest_Logic;
use PHPExperts\LoanCalculators\LoanLogic\LoanLogic;

class PaydayLoanCalculator implements LoanCalculator
{
    /** @var CompoundingInterest_Logic */
    protected $loanLogic;

    public function __construct(LoanLogic $loanLogic = null)
    {
        if (!$loanLogic) {
            $loanLogic = new CompoundingInterest_Logic();
        }
        $this->loanLogic = $loanLogic;
    }

    public function getInstallmentDTO(): InstallmentDTO
    {
        return new InstallmentDTO([
            'period' => 'weekly',
            'units'  => 7,
        ]);
    }

    public function calculateAPR(float $principal, float $totalOwed, int $daysToPay): float
    {
        return $this->loanLogic->calculateAPR($principal, $totalOwed, $daysToPay);
    }

    public function generateBillingStatements(
        float $principal,
        float $apr,
        int $numberOfInstallments,
        float $fees = 0.0): array
    {
        $interestRate = $this->loanLogic->calculateInterestRateFromAPR($apr);
        $statements = $this->loanLogic->generateLoanBillingStatements(
            $principal,
            $interestRate,
            $numberOfInstallments,
            $this->getInstallmentDTO(),
            $fees
        );

        return $statements;
    }
}
