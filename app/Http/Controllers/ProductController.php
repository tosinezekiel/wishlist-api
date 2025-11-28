<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    )
    {}

    public function index(ProductRequest $request): JsonResponse
    {
        $productFilterDTO = $request->toDTO($request);

        $products = $this->productService->list($productFilterDTO);

        return ProductResource::collection($products)
            ->response()
            ->setStatusCode(200);
    }
}
