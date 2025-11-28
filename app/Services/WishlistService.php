<?php

namespace App\Services;

use App\DTOs\AddToWishlistDTO;
use App\DTOs\RemoveFromWishlistDTO;
use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;

class WishlistService
{
    /**
     * Add a product to the user's wishlist.
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function addToWishlist(AddToWishlistDTO $dto): WishlistItem
    {
        Product::findOrFail($dto->productId);

        $exists = WishlistItem::where('user_id', $dto->userId)
            ->where('product_id', $dto->productId)
            ->exists();

        if ($exists) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Product is already in your wishlist.',
                ], 409)
            );
        }

        $wishlistItem = WishlistItem::create([
            'user_id' => $dto->userId,
            'product_id' => $dto->productId,
        ]);

        $wishlistItem->load('product');

        return $wishlistItem;
    }

    public function removeFromWishlist(RemoveFromWishlistDTO $dto): bool
    {
        return WishlistItem::where('user_id', $dto->userId)
            ->where('product_id', $dto->productId)
            ->delete() > 0;
    }

    /**
     * Get the user's wishlist items with products.
     *
     * @return Collection<int, WishlistItem>
     */
    public function getUserWishlist(int $userId): Collection
    {
        return WishlistItem::where('user_id', $userId)
            ->with('product')
            ->get();
    }
}

