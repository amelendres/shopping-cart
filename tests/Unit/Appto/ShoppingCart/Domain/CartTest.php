<?php

namespace Test\Unit\Appto\ShoppingCart\Domain;

use Appto\Common\Infrastructure\PHPUnit\UnitTest;

use Appto\ShoppingCart\Domain\BuyerId;
use Appto\ShoppingCart\Domain\Cart;
use Appto\ShoppingCart\Domain\CartId;
use Appto\ShoppingCart\Domain\ProductDoesNotHaveTheSamePriceException;
use Appto\ShoppingCart\Domain\ProductDoesNotHaveTheSameProductPriceException;
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
        self::assertEquals(0, $cart->summary()->units()->value());
        self::assertEquals(0, $cart->summary()->price()->amount());
    }

    public function testAddFirstProductSuccess() : void
    {
        $cart = CartMother::random();
        $product = ProductLineMother::random();
        $cart->addProduct(
            $product->name(),
            $product->productPrice(),
            $product->units()
        );
        self::assertEquals(1, $cart->productLines()->count());
    }

    public function testAddTwoDifferentProductsSuccess() : void
    {
        $cart = CartMother::random();
        $product = ProductLineMother::random();
        $other = ProductLineMother::random();
        $cart->addProduct(
            $product->name(),
            $product->productPrice(),
            $product->units()
        );
        $cart->addProduct(
            $other->name(),
            $other->productPrice(),
            $other->units()
        );
        self::assertEquals(2, $cart->productLines()->count());
        self::assertEquals(
            $product->units()->add($other->units())->value(),
            $cart->summary()->units()->value());
    }

    public function testAddProductThatAlreadyExists() : void
    {
        $cart = CartMother::random();
        $product = ProductLineMother::random();
        $cart->addProduct(
            $product->name(),
            $product->productPrice(),
            $product->units()
        );
        $cart->addProduct(
            $product->name(),
            $product->productPrice(),
            $product->units()
        );

        self::assertEquals(1, $cart->productLines()->count());
        self::assertEquals(
            $product->units()->multiply(2)->value(),
            $cart->summary()->units()->value()
        );
    }

    public function testAddProductFailsWithSameProductAndDifferentSeller() : void
    {
        $this->expectException(ProductDoesNotHaveTheSameSellerException::class);

        $cart = CartMother::random();
        $product = ProductLineMother::random();
        $other = ProductLineMother::randomWithDifferentSeller($product);
        $cart->addProduct(
            $product->name(),
            $product->productPrice(),
            $product->units()
        );
        $cart->addProduct(
            $other->name(),
            $other->productPrice(),
            $other->units()
        );
    }

    public function testAddProductFailsWithSameProductAndDifferentPrice() : void
    {
        $this->expectException(ProductDoesNotHaveTheSamePriceException::class);

        $cart = CartMother::random();
        $product = ProductLineMother::random();
        $other = ProductLineMother::randomWithDifferentPrice($product);
        $cart->addProduct(
            $product->name(),
            $product->productPrice(),
            $product->units()
        );
        $cart->addProduct(
            $other->name(),
            $other->productPrice(),
            $other->units()
        );
    }

    public function testRemoveProduct() : void
    {
        $cart = CartMother::random();
        $product = ProductLineMother::random();
        $other = ProductLineMother::random();
        $cart->addProduct(
            $product->name(),
            $product->productPrice(),
            $product->units()
        );
        $cart->addProduct(
            $other->name(),
            $other->productPrice(),
            $other->units()
        );

        $cart->removeProduct($product->productPrice()->productId());
        self::assertEquals(1, $cart->productLines()->count());
        self::assertEquals($other, $cart->productLines()->first());
    }

    public function testRemoveAllProducts() : void
    {
        $cart = CartMother::random();
        $product = ProductLineMother::random();
        $other = ProductLineMother::random();
        $cart->addProduct(
            $product->name(),
            $product->productPrice(),
            $product->units()
        );
        $cart->addProduct(
            $other->name(),
            $other->productPrice(),
            $other->units()
        );

        $cart->removeProduct($product->productPrice()->productId());
        $cart->removeProduct($other->productPrice()->productId());
        self::assertEquals(0, $cart->productLines()->count());
        self::assertEquals(0, $cart->summary()->units()->value());
    }

    public function testRemoveProductsFromSeller() : void
    {
        $cart = CartMother::random();
        $product = ProductLineMother::random();
        $other = ProductLineMother::randomWithSeller($product->productPrice()->sellerId());
        $cart->addProduct(
            $product->name(),
            $product->productPrice(),
            $product->units()
        );
        $cart->addProduct(
            $other->name(),
            $other->productPrice(),
            $other->units()
        );

        $cart->removeProductsFromSeller($product->productPrice()->sellerId());

        self::assertTrue($cart->productLines()->isEmpty());
    }

    public function testUpdateProductUnits() : void
    {
        $cart = CartMother::random();
        $product = ProductLineMother::random();
        $newQuantity = $product->units()->multiply(2);
        $cart->addProduct(
            $product->name(),
            $product->productPrice(),
            $product->units()
        );
        $cart->updateProductUnits($product->productPrice(), $newQuantity);

        self::assertEquals(1, $cart->productLines()->count());
        self::assertEquals($newQuantity, $cart->productLines()->first()->units());
    }

    public function testUpdateProductUnitsFailWithDifferentPrice() : void
    {
        $this->expectException(ProductDoesNotHaveTheSameProductPriceException::class);

        $cart = CartMother::random();
        $product = ProductLineMother::random();
        $other = ProductLineMother::randomWithDifferentPrice($product);
        $newQuantity = $product->units()->multiply(2);
        $cart->addProduct(
            $product->name(),
            $product->productPrice(),
            $product->units()
        );

        $cart->updateProductUnits($other->productPrice(), $newQuantity);
    }
}
