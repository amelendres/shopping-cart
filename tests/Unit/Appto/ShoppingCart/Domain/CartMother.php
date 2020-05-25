<?php

namespace Test\Unit\Appto\ShoppingCart\Domain;

use Appto\Common\Infrastructure\PHPUnit\Mother;
use Appto\Common\Infrastructure\PHPUnit\Mother\UuidMother;
use Appto\ShoppingCart\Domain\BuyerId;
use Appto\ShoppingCart\Domain\Cart;
use Appto\ShoppingCart\Domain\CartId;
use Appto\ShoppingCart\Domain\ProductLine;
use Appto\ShoppingCart\Domain\ProductName;

class CartMother extends Mother
{
    public static function random() : Cart
    {
        return new Cart(
            UuidMother::random(CartId::class),
            UuidMother::random(BuyerId::class)
        );
    }

    public static function randomWithProduct(ProductLine $productLine) : Cart
    {
        $cart = new Cart(
            UuidMother::random(CartId::class),
            UuidMother::random(BuyerId::class)
        );

        $cart->addProduct(
            $productLine->name(),
            $productLine->productPrice(),
            $productLine->units()
        );

        return $cart;

    }
}
