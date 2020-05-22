<?php

namespace Appto\ShoppingCart\Domain;

use Appto\Common\Domain\Money\Money;

class ProductLine
{
    private $name;
    private $productPrice;
    private $units;

    public function __construct(
        ProductName $name,
        ProductPrice $productPrice,
        Units $units
    ) {
        $this->name = $name;
        $this->productPrice = $productPrice;
        $this->units = $units;
    }

    public function addUnits(Units $units): ProductLine
    {
        return new self(
            $this->name,
            $this->productPrice,
            $this->units->add($units),
        );
    }

    public function removeUnits(Units $units): ProductLine
    {
        return new self(
            $this->name,
            $this->productPrice,
            $this->units->minus($units),
        );
    }

    public function totalPrice(): Money
    {
        return $this->productPrice->price()->multiply($this->units->value());
    }

    public function name() : ProductName
    {
        return $this->name;
    }

    public function productPrice() : ProductPrice
    {
        return $this->productPrice;
    }

    public function units() : Units
    {
        return $this->units;
    }
}
