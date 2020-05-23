<?php

namespace Appto\ShoppingCart\View;

/**
 * @author  Alfredo Melendres <alfredo.melendres@gmail.com>
 *
 * @OA\Schema(
 *     title="Cart Model",
 *     description="Cart model",
 *     required={"id", "buyerId"},
 *     @OA\Xml(
 *         name="CartView"
 *     )
 * )
 **/
class CartView
{
    /**
     * @OA\Property(format="uuid")
     * @var float
     */
    public $id;

    /**
     * @OA\Property(format="uuid")
     * @var string
     */
    public $buyerId;
}
