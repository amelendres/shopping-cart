<?php

namespace Appto\ShoppingCart\View;

/**
 * @author  Alfredo Melendres <alfredo.melendres@gmail.com>
 *
 * @OA\Schema(
 *     title="Cart Summary Model",
 *     description="Cart summary model",
 *     @OA\Xml(
 *         name="CartSummaryView"
 *     )
 * )
 **/
class CartSummaryView
{
    /**
     * @OA\Property()
     * @var MoneyView
     */
    public $price;

    /**
     * @OA\Property()
     * @var int
     */
    public $units;

    public function __construct(MoneyView $price, int $units)
    {
        $this->price = $price;
        $this->units = $units;
    }
}
