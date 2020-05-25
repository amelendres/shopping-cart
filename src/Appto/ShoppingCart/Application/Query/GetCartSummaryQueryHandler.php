<?php

namespace Appto\ShoppingCart\Application\Query;

use Appto\ShoppingCart\Application\Query\GetCartSummaryQuery;
use Appto\ShoppingCart\Domain\CartRepository;
use Appto\ShoppingCart\Domain\Summary;
use Appto\ShoppingCart\View\CartSummaryView;
use Appto\ShoppingCart\View\MoneyView;

class GetCartSummaryQueryHandler
{
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function __invoke(GetCartSummaryQuery $query) : CartSummaryView
    {
        $cart = $this->cartRepository->get($query->cartId());
        return $this->assembleCartSummaryView($cart->summary());
    }

    private function assembleCartSummaryView(Summary $cartSummary) : CartSummaryView
    {
        return new CartSummaryView(
            new MoneyView($cartSummary->price()->amount(), $cartSummary->price()->currency()),
            $cartSummary->units()->value(),
        );
    }
}
