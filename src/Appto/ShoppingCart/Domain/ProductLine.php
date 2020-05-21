<?php

namespace Appto\ShoppingCart\Domain;

use Appto\Common\Domain\Money\Money;

class ProductLine
{
    private $productLineId;
    private $productPrice;
    private $quantity;

    private $totalPrice;

    public function __construct(
        ProductLineId $productLineId,
        ProductPrice $productPrice,
        Quantity $quantity
    ) {
        $this->productLineId = $productLineId;
        $this->productPrice = $productPrice;
        $this->quantity = $quantity;

        $this->updateTotalPrice();
    }

    private function updateTotalPrice(): void
    {
        $this->totalPrice = $this->productPrice->price()->multiply($this->quantity->value());
    }

    public function add(ProductLine $other): ProductLine
    {
        return new self(
            $this->productLineId,
            new ProductPrice(
                $this->productPrice->productId(),
                $this->productPrice->sellerId(),
                $this->productPrice->price()->add($other->productPrice()->price())
            ),
            $this->quantity->add($other->quantity()),
        );
    }

    public function productLineId() : ProductLineId
    {
        return $this->productLineId;
    }

    public function productPrice() : ProductPrice
    {
        return $this->productPrice;
    }

    public function quantity() : Quantity
    {
        return $this->quantity;
    }

    public function totalPrice(): Money
    {
        return $this->totalPrice;
    }
}
