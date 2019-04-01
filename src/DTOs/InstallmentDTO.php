<?php

namespace PHPExperts\LoanCalculators\DTOs;

use PHPExperts\SimpleDTO\SimpleDTO;

/**
 * Class InstallmentDTO
 * @package PHPExperts\LoanCalculators\DTOs
 * @internal
 *
 * @property-read string $period The adverb for the length (e.g., "yearly", "weekly", "daily").
 * @property-read int    $units  The number of discreet units in the period (e.g., days, weeks, months).
 */
class InstallmentDTO extends SimpleDTO
{
    /** @var string */
    protected $period;

    /** @var int */
    protected $units;
}
