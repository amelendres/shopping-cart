<?php

namespace Appto\ShoppingCart\Domain;

class InvalidQuantityException extends \DomainException
{
    public function __construct(int $value)
    {
        parent::__construct("Invalid quantity <$value>");
    }
}
