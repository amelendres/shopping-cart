<?php

namespace Appto\ShoppingCart\Application\Command;

use Appto\ShoppingCart\Domain\BuyerId;
use Appto\ShoppingCart\Domain\Cart;
use Appto\ShoppingCart\Domain\CartId;
use Appto\ShoppingCart\Domain\CartRepository;

class CreateCartCommandHandler
{
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function __invoke(CreateCartCommand $command) : void
    {
        $this->cartRepository->save(
            new Cart(
                new CartId($command->cartId()),
                new BuyerId($command->buyerId())
            )
        );
    }
}
