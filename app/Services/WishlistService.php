<?php

namespace App\Services;

use App\Models\Product;
use App\Traits\Filterable;
use App\Models\WishlistItem;
use App\DTOs\AddToWishlistDTO;
use App\DTOs\WishlistFilterDTO;
use App\DTOs\RemoveFromWishlistDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class WishlistService
{
    use Filterable;

    protected array $searchable = ['products.name', 'products.description'];
    protected array $sortable = ['products.name', 'products.price', 'wishlist_items.created_at'];


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
    public function getUserWishlist(WishlistFilterDTO $dto): LengthAwarePaginator
    {
        $query = WishlistItem::query()
            ->where('user_id', $dto->userId)
            ->join('products', 'wishlist_items.product_id', '=', 'products.id')
            ->with('product')
            ->select('wishlist_items.*');

        $query = $this->applySearch($query, $dto->search, $this->searchable);
        $query = $this->applySorting($query, $dto->sortBy, $dto->sortDirection, $this->sortable);

        return $query->paginate($dto->perPage);
    }
}

