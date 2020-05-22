<?php

namespace Appto\ShoppingCart\Domain;

class InvalidUnitsException extends \DomainException
{
    public function __construct(int $value)
    {
        parent::__construct("Invalid units <$value>");
    }
}
