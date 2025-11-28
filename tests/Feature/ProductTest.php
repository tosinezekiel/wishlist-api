<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_all_products(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJson([
                'meta' => [
                    'total' => 3,
                    'per_page' => 10,
                ],
            ]);
    }

    public function test_product_resource_has_correct_structure(): void
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $product->id,
                        'name' => 'Test Product',
                        'description' => 'Test Description',
                        'price' => 99.99,
                    ],
                ],
                'meta' => [
                    'total' => 1,
                ],
            ]);
    }

    public function test_products_pagination_works(): void
    {
        Product::factory()->count(25)->create();

        $response = $this->getJson('/api/products?per_page=10');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'current_page' => 1,
                    'per_page' => 10,
                    'total' => 25,
                    'last_page' => 3,
                ],
            ])
            ->assertJsonCount(10, 'data');
    }
}

