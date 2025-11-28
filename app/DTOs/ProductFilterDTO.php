<?php

namespace App\DTOs;

class ProductFilterDTO
{
    public function __construct(
        public readonly int $perPage,
        public readonly ?string $sortBy,
        public readonly ?string $sortDirection,
        public readonly ?string $search
    ) {}
}
