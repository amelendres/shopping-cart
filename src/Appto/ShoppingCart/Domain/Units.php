<?php

namespace Appto\ShoppingCart\Domain;

use Appto\Common\Domain\IntegerValueObject;

class Units extends IntegerValueObject
{
    protected function guard(int $value) : void
    {
        if($value <= 0){
            throw new InvalidUnitsException($value);
        }
    }

    public function multiply(int $factor): Units
    {
        return new self($this->value * $factor);
    }

    public function add(Units $other): Units
    {
        return new self($this->value + $other->value());
    }

    public function minus(Units $other): Units
    {
        return new self($this->value - $other->value());
    }

    public function gt(Units $other): bool
    {
        return $this->value > $other->value();
    }

    public function gte(Units $other): bool
    {
        return $this->value >= $other->value();
    }

    public function lt(Units $other): bool
    {
        return $this->value < $other->value();
    }

    public function lte(Units $other): bool
    {
        return $this->value <= $other->value();
    }
}
