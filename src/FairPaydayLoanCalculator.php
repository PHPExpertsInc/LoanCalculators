<?php declare(strict_types=1);

namespace PHPExperts\LoanCalculators;

use PHPExperts\LoanCalculators\LoanLogic\FairCompounding as FairCompounding_Logic;
use PHPExperts\LoanCalculators\LoanLogic\LoanLogic;

class FairPaydayLoanCalculator extends PaydayLoanCalculator implements LoanCalculator
{
    /** @var FairCompounding_Logic */
    protected $loanLogic;

    public function __construct(LoanLogic $loanLogic = null)
    {
        if (!$loanLogic) {
            $loanLogic = new FairCompounding_Logic();
        }
        $this->loanLogic = $loanLogic;

        parent::__construct($loanLogic);
    }
}
