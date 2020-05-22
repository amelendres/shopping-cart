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
            $this->increaseProductUnits($productLine, $units );
        } elseif (!$productLine->productPrice()->sellerId()->equals($productPrice->sellerId())) {
            throw new ProductDoesNotHaveTheSameSellerException($productPrice->productId());
        } elseif (!$productLine->productPrice()->price()->equals($productPrice->price())) {
            throw new ProductDoesNotHaveTheSamePriceException($productPrice->productId());
        }

    }

    public function updateProductUnits(ProductPrice $productPrice, Units $newQuantity) : void
    {
        $productLine = $this->findProductLine($productPrice);

        if(is_null($productLine)){
            throw new ProductDoesNotHaveTheSameProductPriceException($productPrice->productId());
        }

        if($productLine->units()->equals($newQuantity)){
            return;
        }

        if($newQuantity->gt($productLine->units())){
            $this->increaseProductUnits(
                $productLine,
                $newQuantity->minus($productLine->units())
            );
        }else{
            $this->decreaseProductUnits(
                $productLine,
                $productLine->units()->minus($newQuantity)
            );
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

    private function findProductLine(ProductPrice $productPrice) : ?ProductLine
    {
        $productLine = $this->productLines()->get((string)$productPrice->productId());
        if ($productLine && $productLine->productPrice()->equals($productPrice)) {
            return $productLine;
        }

        return null;
    }

    private function findProductLineByProduct(ProductId $productId) : ?ProductLine
    {
        return $this->productLines()->get((string)$productId);
    }

    private function increaseProductUnits(ProductLine $productLine, Units $units) : void
    {
        if ($this->productLines->containsKey((string)$productLine->productPrice()->productId())) {
            $newProductLine = $productLine->addUnits($units);
            $this->productLines()->set((string)$productLine->productPrice()->productId(), $newProductLine);
        }
    }

    private function decreaseProductUnits(ProductLine $productLine, Units $units) : void
    {
        if ($this->productLines->containsKey((string)$productLine->productPrice()->productId())) {
            $newProductLine = $productLine->removeUnits($units);
            $this->productLines()->set((string)$productLine->productPrice()->productId(), $newProductLine);
        }
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
