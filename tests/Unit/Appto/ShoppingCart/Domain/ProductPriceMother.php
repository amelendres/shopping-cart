<?php

namespace Test\Unit\Appto\ShoppingCart\Domain;

use Appto\Common\Domain\Money\Currency;
use Appto\Common\Domain\Money\Money;
use Appto\Common\Infrastructure\PHPUnit\Mother;
use Appto\Common\Infrastructure\PHPUnit\Mother\UuidMother;
use Appto\ShoppingCart\Domain\ProductId;
use Appto\ShoppingCart\Domain\ProductPrice;
use Appto\ShoppingCart\Domain\SellerId;

class ProductPriceMother extends Mother
{
    public static function random() : ProductPrice
    {
        return new ProductPrice(
            UuidMother::random(ProductId::class),
            UuidMother::random(SellerId::class),
            new Money(
                self::faker()->randomFloat(8, 10, 100),
                new Currency('EUR')
            )
        );
    }
}
