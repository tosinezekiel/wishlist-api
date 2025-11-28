<?php 

namespace App\DTOs\Filter;

use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractDTO
{
    public function __construct(
        public readonly int $perPage,
        public readonly ?string $sortBy,
        public readonly ?string $sortDirection,
        public readonly ?string $search
    ) {}

    public static function fromRequest(FormRequest $request): static
    {
        return new static(
            perPage: $request->integer('per_page', 10),
            sortBy: $request->string('sort_by')->toString(),
            sortDirection: $request->string('sort_direction')->toString() ?: 'desc',
            search: $request->string('search')->toString()
        );
    }
}
