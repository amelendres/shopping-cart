<?php

/**
 * @license Apache 2.0
 */

/**
 * @OA\Info(
 *     description="Shopping Cart API",
 *     version="1.0.0",
 *     title="Appto Shopping Cart",
 *     @OA\Contact(
 *         name="Alfredo Melendres",
 *         email="alfredo.melendres@gmail.com"
 *     )
 *
 * )
 */
/**
 * @OA\Tag(
 *     name="Cart",
 *     description="Everything about Cart"
 * )
 * @OA\Server(
 *      url="{schema}://localhost:8030/api/v1",
 *      description="Appto Shopping Cart API Mocking",
 *      @OA\ServerVariable(
 *          serverVariable="schema",
 *          enum={"https", "http"},
 *          default="http"
 *      )
 * )
 *
 */
