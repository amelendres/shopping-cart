<?php

namespace Appto\ShoppingCart\Domain;

use Appto\Common\Domain\Money\Money;

class CartTotal
{
    private $price;
    private $numberOfProducts;

    public function __construct(Money $price, Quantity $numberOfProducts)
    {
        $this->price = $price;
        $this->numberOfProducts = $numberOfProducts;
    }

    public function price() : Money
    {
        return $this->price;
    }

    public function numberOfProducts() : Quantity
    {
        return $this->numberOfProducts;
    }
}
