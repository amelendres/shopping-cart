<?php

namespace Appto\ShoppingCart\Domain;

interface CartRepository
{
    public function save(Cart $cart): void;
    public function get(string $cartId): Cart;
    public function find(string $cartId): ?Cart;
}
