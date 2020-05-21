<?php

namespace Test\Unit\Appto\ShoppingCart\Domain;

use Appto\Common\Infrastructure\PHPUnit\Mother;
use Appto\Common\Infrastructure\PHPUnit\Mother\UuidMother;
use Appto\ShoppingCart\Domain\ProductId;
use Appto\ShoppingCart\Domain\ProductPrice;
use Appto\ShoppingCart\Domain\SellerId;
use Test\Unit\Appto\Common\Domain\MoneyMother;

class ProductPriceMother extends Mother
{
    public static function random() : ProductPrice
    {
        return new ProductPrice(
            UuidMother::random(ProductId::class),
            UuidMother::random(SellerId::class),
            MoneyMother::random()
        );
    }
}
