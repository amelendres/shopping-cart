<?php

namespace Appto\Common\Domain\Money;

class Money
{
    public const DEFAULT_CURRENCY = 'EUR';

    protected $amount;
    protected $currency;

    public function __construct(float $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function multiply(float $factor): Money
    {
        return new self($this->amount * $factor, $this->currency());
    }

    public function add(Money $other): Money
    {
        return new self($this->amount + $other->amount(), $this->currency());
    }

    public function minus(Money $other): Money
    {
        return new self($this->amount - $other->amount(), $this->currency());
    }

    public function equals(Money $other) : bool
    {
        return $this->currency->equals($other->currency())
            && $this->amount == $other->amount;
    }

    public function amount() : float
    {
        return $this->amount;
    }

    public function currency() : Currency
    {
        return $this->currency;
    }
}
