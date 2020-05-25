<?php

namespace Appto\ShoppingCart\Infrastructure\Persistence\Doctrine\Entity;

use Appto\ShoppingCart\Domain\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class DoctrineCartEntityRepository extends ServiceEntityRepository
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
        parent::__construct($registry, Cart::class);
    }

    public function save(Cart $cart): void
    {
        $this->registry->getManager()->persist($cart);
    }

    public function remove(Cart $cart): void
    {
        $this->registry->getManager()->remove($cart);
    }
}
