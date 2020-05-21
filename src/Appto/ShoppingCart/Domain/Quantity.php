<?php

namespace Appto\ShoppingCart\Domain;

use Appto\Common\Domain\IntegerValueObject;

class Quantity extends IntegerValueObject
{
    protected function guard(int $value) : void
    {
        if($value < 0){
            throw new InvalidQuantityException($value);
        }
    }

    public function multiply(int $factor): Quantity
    {
        return new self($this->value * $factor);
    }

    public function add(Quantity $other): Quantity
    {
        return new self($this->value + $other->value());
    }

    public function minus(Quantity $other): Quantity
    {
        return new self($this->value - $other->value());
    }
}
