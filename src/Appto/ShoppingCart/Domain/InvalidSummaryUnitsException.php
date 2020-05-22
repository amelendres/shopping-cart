<?php

namespace Appto\ShoppingCart\Domain;

class InvalidSummaryUnitsException extends \DomainException
{
    public function __construct(int $value)
    {
        parent::__construct("Invalid summary units <$value>");
    }
}
