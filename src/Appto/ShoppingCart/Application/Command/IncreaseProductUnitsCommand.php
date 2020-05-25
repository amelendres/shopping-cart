<?php

namespace Appto\ShoppingCart\Application\Command;

class IncreaseProductUnitsCommand
{
    private $cartId;
    private $productId;
    private $name;
    private $sellerId;
    private $price;
    private $currency;
    private $qty;

    public function __construct(
        string $cartId,
        string $productId,
        string $name,
        string $sellerId,
        float $price,
        string $currency,
        int $qty
    ) {
        $this->cartId = $cartId;
        $this->productId = $productId;
        $this->name = $name;
        $this->sellerId = $sellerId;
        $this->price = $price;
        $this->currency = $currency;
        $this->qty = $qty;
    }

    public function cartId() : string
    {
        return $this->cartId;
    }

    public function productId() : string
    {
        return $this->productId;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function sellerId() : string
    {
        return $this->sellerId;
    }

    public function price() : float
    {
        return $this->price;
    }

    public function currency() : string
    {
        return $this->currency;
    }

    public function qty() : int
    {
        return $this->qty;
    }
}
