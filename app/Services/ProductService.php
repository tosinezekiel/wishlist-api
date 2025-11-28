<?php

namespace App\Services;

use App\Models\Product;
use App\DTOs\ProductFilterDTO;
use App\Traits\Filterable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    use Filterable;

    protected array $searchable = ['name', 'description'];
    protected array $sortable = ['name', 'price', 'created_at'];

    public function list(ProductFilterDTO $dto): LengthAwarePaginator
    {
        $query = Product::query();

        $query = $this->applySearch($query, $dto->search, $this->searchable);
        $query = $this->applySorting($query, $dto->sortBy, $dto->sortDirection, $this->sortable);

        return $query->paginate($dto->perPage);
    }
}
