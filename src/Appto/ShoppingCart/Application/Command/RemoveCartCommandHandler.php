<?php

namespace Appto\ShoppingCart\Application\Command;

use Appto\ShoppingCart\Domain\BuyerId;
use Appto\ShoppingCart\Domain\CartRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RemoveCartCommandHandler
{
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function __invoke(RemoveCartCommand $command) : void
    {
        $cart = $this->cartRepository->find($command->cartId());
        if (null === $cart) {
            return;
        }
        $this->cartRepository->remove($cart);
    }
}
