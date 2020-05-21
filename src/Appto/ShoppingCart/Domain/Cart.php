<?php

namespace Appto\ShoppingCart\Domain;

use Appto\Common\Domain\Money\Currency;
use Appto\Common\Domain\Money\Money;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Cart
{
    private $id;
    private $buyerId;
    private $productLines;

    private $total;

    public function __construct(CartId $id, BuyerId $buyerId)
    {
        $this->id = $id;
        $this->buyerId = $buyerId;
        $this->productLines = new ArrayCollection();
//        $this->total = null;

        $this->total = new CartTotal(
            new Money(0, new Currency(Money::DEFAULT_CURRENCY)),
            new Quantity(0)
        );
    }

    public function addProductLine(
        ProductName $name,
        ProductPrice $productPrice,
        Quantity $quantity
    ) : void {

        $newProductLine = new ProductLine(
            $name,
            $productPrice,
            $quantity,
        );

        /** @var null|ProductLine $productLine */
        $productLine = $this->findProductLine($newProductLine);
        if ($productLine) {
            $productLine = $productLine->add($newProductLine);
        } else {
            $this->productLines()->add($newProductLine);
        }

        $this->incrementTotal($newProductLine);
    }

    private function findProductline(ProductLine $other) : ?ProductLine
    {
        foreach ($this->productLines() as $productLine) {
            if ($productLine->productPrice()->equals($other->productPrice())) {
                return $productLine;
            }
        }

        return null;
    }

    private function incrementTotal(ProductLine $newProductLine) : void
    {
        $this->total = new CartTotal(
            $this->total->price()->add($newProductLine->totalPrice()),
            $this->total->numberOfProducts()->add($newProductLine->quantity())
        );
    }

    public function id() : CartId
    {
        return $this->id;
    }

    public function buyerId() : BuyerId
    {
        return $this->buyerId;
    }

    public function total() : CartTotal
    {
        return $this->total;
    }

    /**
     * @return Collection|ProductLine[]
     */
    public function productLines() : Collection
    {
        return $this->productLines;
    }
}
