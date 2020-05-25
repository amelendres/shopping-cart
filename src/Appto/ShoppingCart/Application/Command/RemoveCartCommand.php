<?php

namespace Appto\ShoppingCart\Application\Command;

use Appto\Common\Application\Command\Command;

class RemoveCartCommand
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
