<?php

namespace Test\Unit\Appto\ShoppingCart\Domain;

use Appto\Common\Infrastructure\PHPUnit\Mother;
use Appto\Common\Infrastructure\PHPUnit\Mother\UuidMother;
use Appto\ShoppingCart\Domain\BuyerId;
use Appto\ShoppingCart\Domain\Cart;
use Appto\ShoppingCart\Domain\CartId;

class CartMother extends Mother
{
    public static function random() : Cart
    {
        return new Cart(
            UuidMother::random(CartId::class),
            UuidMother::random(BuyerId::class)
        );
    }
}
