<?php

namespace App\Http\Requests\Wishlist;

use App\DTOs\WishlistDTO;
use App\DTOs\WishlistFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

class WishlistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort_by' => ['nullable', 'in:name,price,created_at'],
            'sort_direction' => ['nullable', 'in:asc,desc'],
            'search' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function toDTO(): WishlistFilterDTO
    {
        return WishlistFilterDTO::fromRequest($this);
    }
}
