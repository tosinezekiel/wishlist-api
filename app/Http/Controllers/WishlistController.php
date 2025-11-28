<?php

namespace App\Http\Controllers;

use App\DTOs\AddToWishlistDTO;
use App\DTOs\RemoveFromWishlistDTO;
use App\Http\Requests\Wishlist\AddToWishlistRequest;
use App\Http\Requests\Wishlist\RemoveFromWishlistRequest;
use App\Http\Requests\Wishlist\WishlistRequest;
use App\Http\Resources\WishlistItemResource;
use App\Models\Product;
use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WishlistController extends Controller
{
    public function __construct(
        private readonly WishlistService $wishlistService
    ) {
    }

    public function index(WishlistRequest $request): JsonResponse
    {
        $wishlistFilterDTO = $request->toDTO();
        $wishlistItems = $this->wishlistService->getUserWishlist($wishlistFilterDTO);
        return WishlistItemResource::collection($wishlistItems)
            ->response()
            ->setStatusCode(200);
    }

    public function store(AddToWishlistRequest $request): JsonResponse
    {
        $user = $request->user();
        $dto = AddToWishlistDTO::fromArray([
            'user_id' => $user->id,
            'product_id' => $request->input('product_id'),
        ]);

        $wishlistItem = $this->wishlistService->addToWishlist($dto);

        return response()->json([
            'message' => 'Product added to wishlist successfully.',
            'data' => new WishlistItemResource($wishlistItem),
        ], 201);
    }

    public function destroy(RemoveFromWishlistRequest $request, Product $product): Response
    {
        $user = $request->user();
        $dto = RemoveFromWishlistDTO::fromArray([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->wishlistService->removeFromWishlist($dto);

        return response()->noContent();
    }
}
