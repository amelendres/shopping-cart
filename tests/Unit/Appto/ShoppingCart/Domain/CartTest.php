<?php

namespace Test\Unit\Appto\ShoppingCart\Domain;

use Appto\Common\Infrastructure\PHPUnit\UnitTest;

use Appto\ShoppingCart\Domain\BuyerId;
use Appto\ShoppingCart\Domain\Cart;
use Appto\ShoppingCart\Domain\CartId;
use Appto\ShoppingCart\Domain\ProductDoesNotHaveTheSamePriceException;
use Appto\ShoppingCart\Domain\ProductDoesNotHaveTheSameSellerException;

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
            $product->name(),
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
            $product->name(),
            $product->productPrice(),
            $product->quantity()
        );
        self::assertEquals(1, $cart->productLines()->count());
    }

    public function testAddProductLineThatAlreadyExists() : void
    {
        $cart = CartMother::random();
        $productLine = ProductLineMother::random();
        $cart->addProductLine(
            $productLine->name(),
            $productLine->productPrice(),
            $productLine->quantity()
        );
        $cart->addProductLine(
            $productLine->name(),
            $productLine->productPrice(),
            $productLine->quantity()
        );
        self::assertEquals(1, $cart->productLines()->count());
        self::assertEquals($productLine->quantity()->multiply(2), $cart->total()->numberOfProducts());
    }

    public function testAddProductLineFailsWithSameProductAndDifferentSeller() : void
    {
        $this->expectException(ProductDoesNotHaveTheSameSellerException::class);

        $cart = CartMother::random();
        $productLine = ProductLineMother::random();
        $other = ProductLineMother::randomWithDifferentSeller($productLine);
        $cart->addProductLine(
            $productLine->name(),
            $productLine->productPrice(),
            $productLine->quantity()
        );
        $cart->addProductLine(
            $other->name(),
            $other->productPrice(),
            $other->quantity()
        );
    }

    public function testAddProductLineFailsWithSameProductAndDifferentPrice() : void
    {
        $this->expectException(ProductDoesNotHaveTheSamePriceException::class);

        $cart = CartMother::random();
        $productLine = ProductLineMother::random();
        $other = ProductLineMother::randomWithDifferentPrice($productLine);
        $cart->addProductLine(
            $productLine->name(),
            $productLine->productPrice(),
            $productLine->quantity()
        );
        $cart->addProductLine(
            $other->name(),
            $other->productPrice(),
            $other->quantity()
        );
    }

    public function testRemoveProductLineByProduct() : void
    {
        $cart = CartMother::random();
        $productLine = ProductLineMother::random();
        $other = ProductLineMother::random();
        $cart->addProductLine(
            $productLine->name(),
            $productLine->productPrice(),
            $productLine->quantity()
        );
        $cart->addProductLine(
            $other->name(),
            $other->productPrice(),
            $other->quantity()
        );

        $cart->removeProductLine($productLine->productPrice()->productId());
        self::assertEquals(1, $cart->productLines()->count());
        self::assertEquals($other, $cart->productLines()->first());
    }

    public function testRemoveProductLinesFromSeller() : void
    {
        $cart = CartMother::random();
        $productLine = ProductLineMother::random();
        $other = ProductLineMother::randomWithSeller($productLine->productPrice()->sellerId());
        $cart->addProductLine(
            $productLine->name(),
            $productLine->productPrice(),
            $productLine->quantity()
        );
        $cart->addProductLine(
            $other->name(),
            $other->productPrice(),
            $other->quantity()
        );

        $cart->removeProductLinesFromSeller($productLine->productPrice()->sellerId());
        self::assertTrue($cart->productLines()->isEmpty());
    }

}
