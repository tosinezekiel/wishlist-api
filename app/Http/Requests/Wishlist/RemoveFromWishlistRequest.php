<?php

namespace App\Http\Requests\Wishlist;

use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RemoveFromWishlistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $product = $this->route('product');

        $exists = WishlistItem::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        if (!$user || !$product || !$exists) {
            throw new HttpResponseException(
                response()->json(['message' => 'This action is unauthorized.'], 403)
            );
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
