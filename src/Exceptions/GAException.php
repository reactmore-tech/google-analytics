<?php

namespace ReactMoreTech\Analytics\Exceptions;

use DateTimeInterface;

class GAException extends \RuntimeException
{
    public static function forInvalidConfiguration()
    {
        return new self(lang('GA.invalidConfiguration'));
    }

    public static function startDateCannotBeAfterEndDate(DateTimeInterface $startDate, DateTimeInterface $endDate): static
    {
        return new static("Start date `{$startDate->format('Y-m-d')}` cannot be after end date `{$endDate->format('Y-m-d')}`.");
    }
}
