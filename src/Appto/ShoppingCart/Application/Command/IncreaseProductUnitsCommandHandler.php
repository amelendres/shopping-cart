<?php

namespace Appto\ShoppingCart\Application\Command;

use Appto\Common\Domain\Money\Currency;
use Appto\Common\Domain\Money\Money;
use Appto\ShoppingCart\Domain\CartRepository;
use Appto\ShoppingCart\Domain\ProductId;
use Appto\ShoppingCart\Domain\ProductPrice;
use Appto\ShoppingCart\Domain\SellerId;
use Appto\ShoppingCart\Domain\Units;

class IncreaseProductUnitsCommandHandler
{
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function __invoke(IncreaseProductUnitsCommand $command) : void
    {
        $cart = $this->cartRepository->get($command->cartId());
        $cart->increaseProductUnits(
            new ProductPrice(
                new ProductId($command->productId()),
                new SellerId($command->sellerId()),
                new Money(
                    $command->price(),
                    new Currency($command->currency())
                )
            ),
            new Units($command->qty()),
        );
        $this->cartRepository->save($cart);
    }
}
