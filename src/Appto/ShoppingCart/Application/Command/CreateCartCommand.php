<?php

namespace Appto\ShoppingCart\Application\Command;

use Appto\Common\Application\Command\Command;

class CreateCartCommand
{
    private $cartId;
    private $buyerId;

    public function __construct(string $cartId, string $buyerId)
    {
        $this->cartId = $cartId;
        $this->buyerId = $buyerId;
    }

    public function cartId() : string
    {
        return $this->cartId;
    }

    public function buyerId() : string
    {
        return $this->buyerId;
    }
}
