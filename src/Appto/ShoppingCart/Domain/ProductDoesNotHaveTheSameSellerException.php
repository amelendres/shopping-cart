<?php

namespace Appto\ShoppingCart\Domain;

class ProductDoesNotHaveTheSameSellerException extends \DomainException
{
    public function __construct(string $value)
    {
        parent::__construct("Product <$value> does not have the same seller ");
    }
}
