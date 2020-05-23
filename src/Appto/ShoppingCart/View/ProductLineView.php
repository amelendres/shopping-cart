<?php

namespace Appto\ShoppingCart\View;

/**
 * @author  Alfredo Melendres <alfredo.melendres@gmail.com>
 *
 * @OA\Schema(
 *     title="ProductLine model",
 *     description="ProductLine model",
 *     required={"productId", "name", "price","sellerId", "qty"},
 *     @OA\Xml(
 *         name="ProductLineView"
 *     )
 * )
 **/
class ProductLineView
{
    /**
     * @OA\Property(format="uuid")
     * @var string
     */
    public $productId;

    /**
     * @OA\Property()
     * @var string
     */
    public $name;

    /**
     * @OA\Property()
     * @var MoneyView
     */
    public $price;

    /**
     * @OA\Property(format="uuid")
     * @var string
     */
    public $sellerId;

    /**
     * @OA\Property()
     * @var int
     */
    public $qty;

}
