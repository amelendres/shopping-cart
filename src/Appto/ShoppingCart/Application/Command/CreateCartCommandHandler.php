<?php

namespace Appto\ShoppingCart\Application\Command;

use Appto\ShoppingCart\Domain\BuyerId;
use Appto\ShoppingCart\Domain\Cart;
use Appto\ShoppingCart\Domain\CartId;
use Appto\ShoppingCart\Domain\CartRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateCartCommandHandler
{
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function __invoke(CreateCartCommand $command) : void
    {
        $cart = $this->cartRepository->find($command->cartId());
        if ($cart) {
            throw new BadRequestHttpException();
        }

        $this->cartRepository->save(
            new Cart(
                new CartId($command->cartId()),
                new BuyerId($command->buyerId())
            )
        );
    }
}
