<?php

namespace Test\Unit\Appto\Common\Domain;

use Appto\Common\Domain\Money\Currency;
use Appto\Common\Domain\Money\Money;
use Appto\Common\Infrastructure\PHPUnit\Mother;

class MoneyMother extends Mother
{
    public static function random() : Money
    {
        return new Money(
            self::faker()->randomFloat(8, 5, 100),
            new Currency('EUR')
        );

    }
}
