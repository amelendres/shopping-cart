<?php

namespace Appto\ShoppingCart\Application\Query;

class GetCartSummaryQuery
{
    private $cartId;

    public function __construct(string $cartId)
    {
        $this->cartId = $cartId;
    }

    public function cartId() : string
    {
        return $this->cartId;
    }
}
