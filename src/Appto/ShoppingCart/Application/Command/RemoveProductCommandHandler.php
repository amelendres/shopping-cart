<?php

namespace Appto\ShoppingCart\Application\Command;

use Appto\ShoppingCart\Domain\CartRepository;
use Appto\ShoppingCart\Domain\ProductId;

class RemoveProductCommandHandler
{
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function __invoke(RemoveProductCommand $command) : void
    {
        $cart = $this->cartRepository->get($command->cartId());
        $cart->removeProduct(new ProductId($command->productId()));

        $this->cartRepository->save($cart);
    }
}
