<?php

namespace Appto\ShoppingCart\Domain;

use Appto\Common\Domain\Money\Money;

class ProductPrice
{
    private $productId;
    private $sellerId;
    private $price;

    public function __construct(
        ProductId $productId,
        SellerId $sellerId,
        Money $price
    ) {
        $this->productId = $productId;
        $this->sellerId = $sellerId;
        $this->price = $price;
    }

    public function equals(ProductPrice $other): bool
    {
        return $this->productId->equals($other->productId)
            && $this->sellerId->equals($other->sellerId)
            && $this->price->equals($other->price);

    }

    public function productId() : ProductId
    {
        return $this->productId;
    }

    public function sellerId() : SellerId
    {
        return $this->sellerId;
    }

    public function price() : Money
    {
        return $this->price;
    }
}
