<?php

namespace App\Services;

use App\Models\Product;
use App\DTOs\ProductFilterDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function list(ProductFilterDTO $dto): LengthAwarePaginator
    {
        return Product::query()
            ->when($dto->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })->when($dto->sortBy, function ($query, $sortBy) use ($dto) {
                $query->orderBy($sortBy, $dto->sortDirection);
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })->paginate($dto->perPage);
    }
}
