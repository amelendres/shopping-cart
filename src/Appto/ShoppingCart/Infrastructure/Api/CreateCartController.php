<?php

namespace Appto\ShoppingCart\Infrastructure\Api;

use Appto\ShoppingCart\Application\Command\CreateCartCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/carts", name="carts_"
 * )
 */
class CreateCartController extends AbstractController
{
    /**
     * @Route(
     *     "",
     *     methods={"POST"},
     *     name="createCart"
     * )
     *
     * @OA\Post(
     *     path="/carts",
     *     tags={"Cart"},
     *     summary="Create Cart",
     *     description="Create a cart",
     *     operationId="createCart",
     *     @OA\Response(
     *          response=200,
     *          description="Success"
     *     ),
     *     @OA\RequestBody(
     *          request="Cart",
     *          description="Cart object",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CartView")
     *      )
     * )
     */
    public function createCart(Request $request, MessageBusInterface $commandBus)
    {
        $body = json_decode((string)$request->getContent());
        $commandBus->dispatch(new CreateCartCommand(
            $body->id,
            $body->buyerId
        ));

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
