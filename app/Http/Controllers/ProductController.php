<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ProductRequest;
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
        $productDTO = $request->toDTO($request);

        // return response()->json(Product::all());

        $products = $this->productService->list($productDTO);

        return ProductResource::collection($products)
            ->response()
            ->setStatusCode(200);
    }
}
