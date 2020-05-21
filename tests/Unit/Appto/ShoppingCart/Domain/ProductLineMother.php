<?php

namespace Test\Unit\Appto\ShoppingCart\Domain;

use Appto\Common\Infrastructure\PHPUnit\Mother;
use Appto\Common\Infrastructure\PHPUnit\Mother\UuidMother;
use Appto\ShoppingCart\Domain\ProductLine;
use Appto\ShoppingCart\Domain\ProductName;
use Appto\ShoppingCart\Domain\Quantity;

class ProductLineMother extends Mother
{
    public static function random() : ProductLine
    {
        return new ProductLine(
            UuidMother::random(ProductName::class),
            ProductPriceMother::random(),
            new Quantity(self::faker()->numberBetween(1,10))
        );
    }
}
