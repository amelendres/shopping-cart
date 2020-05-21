<?php

namespace Test\Unit\Appto\ShoppingCart\Domain;

use Appto\Common\Domain\Money\Money;
use Appto\Common\Infrastructure\PHPUnit\Mother;
use Appto\Common\Infrastructure\PHPUnit\Mother\UuidMother;
use Appto\ShoppingCart\Domain\ProductLine;
use Appto\ShoppingCart\Domain\ProductName;
use Appto\ShoppingCart\Domain\ProductPrice;
use Appto\ShoppingCart\Domain\Quantity;
use Appto\ShoppingCart\Domain\SellerId;
use Appto\ShoppingCart\Infrastructure\PHPUnit\Mother\SimpleVOMother;

class ProductLineMother extends Mother
{
    public static function random() : ProductLine
    {
        return new ProductLine(
            SimpleVOMother::random(ProductName::class, self::faker()->text(48)),
            ProductPriceMother::random(),
            new Quantity(self::faker()->numberBetween(1,10))
        );
    }

    public static function randomWithDifferentSeller(ProductLine $productLine) : ProductLine
    {
        return new ProductLine(
            $productLine->name(),
            new ProductPrice(
                $productLine->productPrice()->productId(),
                UuidMother::random(SellerId::class),
                new Money(self::faker()->numberBetween(1,100), $productLine->productPrice()->price()->currency())
            ),
            new Quantity(self::faker()->numberBetween(1,20))
        );
    }
}
