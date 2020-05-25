<?php

namespace Appto\ShoppingCart\Infrastructure\Api;

use Appto\Common\Infrastructure\Symfony\Messenger\QueryBus;
use Appto\ShoppingCart\Application\Query\GetCartSummaryQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/carts", name="carts_"
 * )
 */
class GetCartSummaryController extends AbstractController
{
    /**
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="getCartSummary"
     * )
     *
     * @OA\Get(
     *     path="/carts/{id}",
     *     tags={"Cart"},
     *     summary="Get cart summary",
     *     description="Get cart summary",
     *     operationId="getCartSummary",
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
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/CartSummaryView")
     *     )
     * )
     */
    public function getCartSummary(Request $request, string $id, QueryBus $queryBus)
    {
        return new JsonResponse($queryBus->query( new GetCartSummaryQuery($id)), Response::HTTP_OK);
    }
}
