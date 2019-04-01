<?php declare(strict_types=1);

namespace PHPExperts\LoanCalculators;

use PHPExperts\LoanCalculators\DTOs\InstallmentDTO;

interface LoanCalculator
{
    public function getInstallmentDTO(): InstallmentDTO;
}
