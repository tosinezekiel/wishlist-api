<?php

namespace App\Http\Requests;

use App\DTOs\ProductFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort_by' => ['nullable', 'in:name,price,created_at'],
            'sort_direction' => ['nullable', 'in:asc,desc'],
            'search' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function toDTO(): ProductFilterDTO
    {
        return new ProductFilterDTO(
            perPage: $this->integer('per_page', 10),
            sortBy: $this->string('sort_by')->toString(),
            sortDirection: $this->string('sort_direction')->toString() ?: 'desc',
            search: $this->string('search')->toString()
        );
    }
}
