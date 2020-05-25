<?php

namespace Appto\ShoppingCart\Infrastructure\Api;

use Appto\ShoppingCart\Application\Command\IncreaseProductUnitsCommand;
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
class IncreaseProductUnitsController extends AbstractController
{
    /**
     * @Route(
     *     "/{id}/products/increase",
     *     methods={"POST"},
     *     name="increaseProductUnits"
     * )
     *
     * @OA\Post(
     *     path="/carts/{id}/products/increase",
     *     tags={"Cart"},
     *     summary="Increase Product Units",
     *     description="Increase product units",
     *     operationId="increaseProductUnits",
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
     *     ),
     *     @OA\RequestBody(
     *          request="Product",
     *          description="Product object",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ProductLineView")
     *      )
     * )
     */
    public function increaseProductUnits(Request $request, string $id, MessageBusInterface $commandBus)
    {
        $body = json_decode((string)$request->getContent());
        $commandBus->dispatch(new IncreaseProductUnitsCommand(
            $id,
            $body->productId,
            $body->name,
            $body->sellerId,
            $body->price->amount,
            $body->price->currency,
            $body->qty
        ));

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
