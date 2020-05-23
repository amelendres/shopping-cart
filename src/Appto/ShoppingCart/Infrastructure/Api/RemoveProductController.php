<?php

namespace Appto\ShoppingCart\Infrastructure\Api;

use Appto\ShoppingCart\Application\Command\RemoveProductCommand;
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
class RemoveProductController extends AbstractController
{
    /**
     * @Route(
     *     "/{id}/products/{productId}",
     *     methods={"DELETE"},
     *     name="removeProduct"
     * )
     *
     * @OA\Delete(
     *     path="/carts/{id}/products/{productId}",
     *     tags={"Cart"},
     *     summary="Remove a Product from Cart",
     *     description="Remove a product form cart",
     *     operationId="removeProduct",
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
     *      @OA\Parameter(
     *          name="productId",
     *          in="path",
     *          description="Product id to delete",
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
    public function removeProduct(Request $request, string $id, string $productId, MessageBusInterface $commandBus)
    {
        $body = json_decode((string)$request->getContent());
        $commandBus->dispatch(new RemoveProductCommand(
            $id,
            $productId
        ));

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
