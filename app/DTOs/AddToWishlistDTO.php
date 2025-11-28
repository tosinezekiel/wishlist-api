<?php

namespace App\DTOs;

readonly class AddToWishlistDTO
{
    public function __construct(
        public int $userId,
        public int $productId,
    ) {
    }
    
    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            productId: $data['product_id'],
        );
    }
}

