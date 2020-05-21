<?php

namespace Test\Unit\Appto\ShoppingCart\Domain;

use Appto\Common\Infrastructure\PHPUnit\UnitTest;

use Appto\ShoppingCart\Domain\BuyerId;
use Appto\ShoppingCart\Domain\Cart;
use Appto\ShoppingCart\Domain\CartId;

class CartTest extends UnitTest
{
    public function testCreateAnEmptyCartSuccess() : void
    {
        $cart = new Cart(
            new CartId($this->faker->uuid),
            new BuyerId($this->faker->uuid)
        );

        self::assertTrue($cart->productLines()->isEmpty());
    }

    public function testAddFirstProductLineToCartSuccess() : void
    {
        $cart = CartMother::random();
        $product = ProductLineMother::random();
        $cart->addProductLine(
            $product->productLineId(),
            $product->productPrice(),
            $product->quantity()
        );
        self::assertEquals(1, $cart->productLines()->count());
    }

    public function testAddProductLineToCartSuccess() : void
    {
        $cart = CartMother::random();
        $product = ProductLineMother::random();
        $cart->addProductLine(
            $product->productLineId(),
            $product->productPrice(),
            $product->quantity()
        );
        self::assertEquals(1, $cart->productLines()->count());
    }

    public function testRemoveProductLineFromCart() : void
    {
        $cart = CartMother::random();
        $product = ProductLineMother::random();
        $other = ProductLineMother::random();
        $cart->addProductLine(
            $product->productLineId(),
            $product->productPrice(),
            $product->quantity()
        );
        $cart->addProductLine(
            $other->productLineId(),
            $other->productPrice(),
            $other->quantity()
        );

        $cart->removeProduct($product->productLineId(), $product->productPrice()->sellerId());
        self::assertEquals(1, $cart->productLines()->count());
        self::assertEquals($other->productLineId(), $cart->productLines()->first()->productLineId());
    }

}
