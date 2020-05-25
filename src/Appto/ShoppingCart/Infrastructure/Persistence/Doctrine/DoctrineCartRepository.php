<?php

namespace Appto\ShoppingCart\Infrastructure\Persistence\Doctrine;

use Appto\ShoppingCart\Domain\Cart;
use Appto\ShoppingCart\Domain\CartRepository;
use Appto\ShoppingCart\Infrastructure\Persistence\Doctrine\Entity\DoctrineCartEntityRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DoctrineCartRepository implements CartRepository
{
    private $repository;

    public function __construct(DoctrineCartEntityRepository $doctrineRepository)
    {
        $this->repository = $doctrineRepository;
    }

    public function find(string $cartId) : ?Cart
    {
        /** @var null|Cart $cart */
        $cart = $this->repository->find($cartId);
        return $cart;
    }

    public function get(string $cartId) : Cart
    {
        $cart = $this->find($cartId);
        if (is_null($cart)){
            throw new BadRequestHttpException();
        }

        return $cart;
    }

    public function save(Cart $cart) : void
    {
        $this->repository->save($cart);
    }

    public function remove(Cart $cart) : void
    {
        $this->repository->remove($cart);
    }
}
