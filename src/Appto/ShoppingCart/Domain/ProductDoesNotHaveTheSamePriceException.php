<?php

namespace Appto\ShoppingCart\Domain;

class ProductDoesNotHaveTheSamePriceException extends \DomainException
{
    public function __construct(string $value)
    {
        parent::__construct("Product <$value> does not have the same price");
    }
}
