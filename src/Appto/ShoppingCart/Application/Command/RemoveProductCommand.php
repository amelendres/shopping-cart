<?php

namespace Appto\ShoppingCart\Application\Command;

class RemoveProductCommand
{
    private $cartId;
    private $productId;

    public function __construct(string $cartId, string $productId)
    {
        $this->cartId = $cartId;
        $this->productId = $productId;
    }

    public function cartId() : string
    {
        return $this->cartId;
    }

    public function productId() : string
    {
        return $this->productId;
    }
}
