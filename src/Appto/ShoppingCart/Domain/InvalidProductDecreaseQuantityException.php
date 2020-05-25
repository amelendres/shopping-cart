<?php

namespace Appto\ShoppingCart\Domain;

class InvalidProductDecreaseQuantityException extends \DomainException
{
    public function __construct(int $value)
    {
        parent::__construct("Invalid product decrease quantity <$value>");
    }
}
