<?php

namespace Appto\ShoppingCart\Domain;

use Appto\Common\Domain\Money\Currency;
use Appto\Common\Domain\Money\Money;
use Appto\Common\Domain\Number\NaturalNumber;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Cart
{
    private $id;
    private $buyerId;
    private $productLines;

    public function __construct(CartId $id, BuyerId $buyerId)
    {
        $this->id = $id;
        $this->buyerId = $buyerId;
        $this->productLines = new ArrayCollection();
    }

    public function addProduct(
        ProductName $name,
        ProductPrice $productPrice,
        Units $units
    ) : void {

        $newProductLine = new ProductLine(
            $name,
            $productPrice,
            $units,
        );

        $productLine = $this->findProductLineByProduct($productPrice->productId());
        if (is_null($productLine)) {
            $this->productLines()->set((string)$newProductLine->productPrice()->productId(), $newProductLine);

        } elseif ($productLine->productPrice()->equals($productPrice)) {
            $this->increaseProductUnits($productLine->productPrice(), $units);

        } elseif (!$productLine->productPrice()->sellerId()->equals($productPrice->sellerId())) {
            throw new ProductDoesNotHaveTheSameSellerException($productPrice->productId());
        } elseif (!$productLine->productPrice()->price()->equals($productPrice->price())) {
            throw new ProductDoesNotHaveTheSamePriceException($productPrice->productId());
        }
    }

    public function removeProduct(ProductId $productId) : void
    {
        $productLine = $this->findProductLineByProduct($productId);
        if ($productLine) {
            $this->productLines()->removeElement($productLine);
        }
    }

    public function removeProductsFromSeller(SellerId $sellerId) : void
    {
        $productLinesToRemove = $this->findProductLinesBySeller($sellerId);
        foreach ($productLinesToRemove as $productLine) {
            $this->productLines()->removeElement($productLine);
        }
    }

    public function productLine(ProductPrice $productPrice) : ProductLine
    {
        foreach ($this->productLines() as $productLine) {
            if ($productLine->productPrice()->equals($productPrice)) {
                return $productLine;
            }
        }

        throw new ProductDoesNotHaveTheSameProductPriceException($productPrice->productId());
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

    public function increaseProductUnits(ProductPrice $productPrice, Units $quantity) : void
    {
        $currentProductLine = $this->productLine($productPrice);

        $newProductLine = new ProductLine(
            $currentProductLine->name(),
            $currentProductLine->productPrice(),
            $currentProductLine->units()->add($quantity),
        );
        $this->productLines->removeElement($currentProductLine);
        $this->productLines->add($newProductLine);

    }

    public function decreaseProductUnits(ProductPrice $productPrice, Units $quantity) : void
    {
        $currentProductLine = $this->productLine($productPrice);

        if($currentProductLine->units()->lt($quantity)){
            throw new InvalidProductDecreaseQuantityException($quantity->value());
        }

        if($currentProductLine->units()->equals($quantity)){
            $this->removeProduct($currentProductLine->productPrice()->productId());
            return;
        }

        $newProductLine = new ProductLine(
            $currentProductLine->name(),
            $currentProductLine->productPrice(),
            $currentProductLine->units()->minus($quantity),
        );
        $this->productLines->removeElement($currentProductLine);
        $this->productLines->add($newProductLine);
    }

    /**
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

    public function summary() : Summary
    {
        $summary = new Summary(
            new Money(0, new Currency(Money::DEFAULT_CURRENCY)),
            new NaturalNumber(0)
        );

        foreach ($this->productLines() as $productLine) {
            $summary = new Summary(
                $summary->price()->add($productLine->totalPrice()),
                $summary->units()->add(new NaturalNumber($productLine->units()->value()))
            );
        }

        return $summary;
    }

    public function id() : CartId
    {
        return $this->id;
    }

    public function buyerId() : BuyerId
    {
        return $this->buyerId;
    }

    /**
     * @return Collection|ProductLine[]
     */
    public function productLines() : Collection
    {
        return $this->productLines;
    }
}
