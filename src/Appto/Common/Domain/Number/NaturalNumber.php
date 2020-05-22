<?php

namespace Appto\Common\Domain\Number;

use Appto\Common\Domain\IntegerValueObject;

class NaturalNumber extends IntegerValueObject
{
    protected function guard(int $value) : void
    {
        if($value < 0){
            throw new InvalidNaturalNumberException($value);
        }
    }

    public function add(NaturalNumber $other): NaturalNumber
    {
        return new self($this->value + $other->value());
    }
}
