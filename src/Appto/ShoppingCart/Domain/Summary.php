<?php

namespace Appto\ShoppingCart\Domain;

use Appto\Common\Domain\Money\Money;
use Appto\Common\Domain\Number\NaturalNumber;

class Summary
{
    private $price;
    private $units;

    public function __construct(Money $price, NaturalNumber $units)
    {
        $this->price = $price;
        $this->units = $units;
    }

    public function price() : Money
    {
        return $this->price;
    }

    public function units() : NaturalNumber
    {
        return $this->units;
    }
}
