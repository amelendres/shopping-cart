<?php

namespace Appto\ShoppingCart\Infrastructure\Api;

use Appto\ShoppingCart\Application\Command\RemoveCartCommand;
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
class RemoveCartController extends AbstractController
{
    /**
     * @Route(
     *     "/{id}",
     *     methods={"DELETE"},
     *     name="removeCart"
     * )
     *
     * @OA\Delete(
     *     path="/carts/{id}",
     *     tags={"Cart"},
     *     summary="Remove Cart",
     *     description="Remove cart",
     *     operationId="removeCart",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Cart id",
     *          required=true,
     *              @OA\Schema(
     *                  type="string",
     *                  format="uuid"
     *              )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Success"
     *     )
     * )
     */
    public function removeCart(Request $request, string $id, MessageBusInterface $commandBus)
    {
        $commandBus->dispatch(new RemoveCartCommand($id));

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
