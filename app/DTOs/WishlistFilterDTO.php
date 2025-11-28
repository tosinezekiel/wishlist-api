<?php

namespace App\DTOs;

use App\DTOs\Filter\AbstractDTO;
use Illuminate\Foundation\Http\FormRequest;

class WishlistFilterDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $userId,
        int $perPage,
        ?string $sortBy,
        ?string $sortDirection,
        ?string $search,
    ) {
        parent::__construct($perPage, $sortBy, $sortDirection, $search);
    }

    public static function fromRequest(FormRequest $request): static
    {
        return new static(
            userId: $request->user()->id,
            perPage: $request->integer('per_page', 10),
            sortBy: $request->string('sort_by')->toString(),
            sortDirection: $request->string('sort_direction')->toString() ?: 'desc',
            search: $request->string('search')->toString()
        );
    }
}
