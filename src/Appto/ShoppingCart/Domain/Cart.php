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
        $productLine = $this->findProductLineByProduct($productPrice->productId());
        if (is_null($productLine)) {
            $this->productLines()->add($newProductLine);
        } elseif ($productLine->productPrice()->equals($productPrice)) {
            $productLine = $productLine->add($newProductLine);
        } elseif (!$productLine->productPrice()->sellerId()->equals($productPrice->sellerId())) {
            throw new ProductDoesNotHaveTheSameSellerException($productPrice->productId());
        } elseif (!$productLine->productPrice()->price()->equals($productPrice->price())) {
            throw new ProductDoesNotHaveTheSamePriceException($productPrice->productId());
        }

        $this->incrementTotal($newProductLine);
    }

    private function incrementTotal(ProductLine $newProductLine) : void
    {
        $this->total = new CartTotal(
            $this->total->price()->add($newProductLine->totalPrice()),
            $this->total->numberOfProducts()->add($newProductLine->quantity())
        );
    }

    private function decrementTotal(ProductLine $productLine) : void
    {
        $this->total = new CartTotal(
            $this->total->price()->minus($productLine->totalPrice()),
            $this->total->numberOfProducts()->minus($productLine->quantity())
        );
    }

    public function removeProductLine(ProductId $productId) : void
    {
        $productLine = $this->findProductLineByProduct($productId);
        if ($productLine) {
            $this->productLines()->removeElement($productLine);
            $this->decrementTotal($productLine);
        }
    }

    public function removeProductLinesFromSeller(SellerId $sellerId) : void
    {
        $productLinesToRemove = $this->findProductLinesBySeller($sellerId);
        foreach ($productLinesToRemove as $productLine) {
            $this->productLines()->removeElement($productLine);
            $this->decrementTotal($productLine);
        }
    }

    private function findProductLineByProduct(ProductId $productId) : ?ProductLine
    {
        foreach ($this->productLines() as $productLine) {
            if ($productLine->productPrice()->productId()->equals($productId)) {
                return $productLine;
            }
        }

        return null;
    }

    /**
     * @param SellerId $sellerId
     * @return array|ProductLine[]
     */
    private function findProductLinesBySeller(SellerId $sellerId) : array
    {
        $productLines = [];
        foreach ($this->productLines() as $line) {
            if ($line->productPrice()->sellerId()->equals($sellerId)) {
                $productLines[] = $line;
            }
        }

        return $productLines;
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
