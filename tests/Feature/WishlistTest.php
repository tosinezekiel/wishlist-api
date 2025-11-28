<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\WishlistItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_product_to_wishlist(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/wishlist', [
                'product_id' => $product->id,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'product' => [
                        'id' => $product->id,
                    ],
                ],
            ]);

        $this->assertDatabaseHas('wishlist_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_user_cannot_add_duplicate_product_to_wishlist(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/wishlist', [
                'product_id' => $product->id,
            ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/wishlist', [
                'product_id' => $product->id,
            ]);

        $response->assertStatus(409)
            ->assertJson(['message' => 'Product is already in your wishlist.']);
    }

    public function test_user_can_view_their_wishlist(): void
    {
        $user = User::factory()->create();
        $products = Product::factory()->count(3)->create();
        $token = $user->createToken('test-token')->plainTextToken;

        foreach ($products as $product) {
            WishlistItem::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
        }

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/wishlist');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_user_can_remove_product_from_wishlist(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        WishlistItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/wishlist/{$product->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('wishlist_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_user_cannot_add_nonexistent_product_to_wishlist(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/wishlist', [
                'product_id' => 99999,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['product_id']);
    }

    public function test_user_cannot_remove_product_not_in_wishlist(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/wishlist/{$product->id}");

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_wishlist(): void
    {
        $response = $this->getJson('/api/wishlist');

        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_cannot_add_to_wishlist(): void
    {
        $product = Product::factory()->create();

        $response = $this->postJson('/api/wishlist', [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(401);
    }

    public function test_user_can_only_see_their_own_wishlist(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        WishlistItem::create([
            'user_id' => $user1->id,
            'product_id' => $product1->id,
        ]);

        WishlistItem::create([
            'user_id' => $user2->id,
            'product_id' => $product2->id,
        ]);

        $token = $user1->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/wishlist');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'product' => [
                            'id' => $product1->id,
                        ],
                    ],
                ],
            ]);
    }

    public function test_user_cannot_remove_another_users_wishlist_item(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $product = Product::factory()->create();

        WishlistItem::create([
            'user_id' => $user2->id,
            'product_id' => $product->id,
        ]);

        $token = $user1->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/wishlist/{$product->id}");

        $response->assertStatus(403);
    }

    public function test_wishlist_returns_empty_array_when_no_items(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/wishlist');

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }
}

