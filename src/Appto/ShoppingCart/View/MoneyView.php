<?php

namespace Appto\ShoppingCart\View;


/**
 * @author  Alfredo Melendres <alfredo.melendres@gmail.com>
 *
 * @OA\Schema(
 *     title="Money Model",
 *     description="Money model",
 *     required={"amount", "currency"},
 *     @OA\Xml(
 *         name="MoneyView"
 *     )
 * )
 **/
class MoneyView
{
    /**
     * @OA\Property()
     * @var float
     */
    public $amount;

    /**
     * @OA\Property()
     * @var string
     */
    public $currency;

    public function __construct(float $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }
}
