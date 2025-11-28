<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Laptop Pro 15"',
                'description' => 'High-performance laptop with 16GB RAM and 512GB SSD',
                'price' => 1299.99,
            ],
            [
                'name' => 'Wireless Mouse',
                'description' => 'Ergonomic wireless mouse with long battery life',
                'price' => 29.99,
            ],
            [
                'name' => 'Mechanical Keyboard',
                'description' => 'RGB mechanical keyboard with Cherry MX switches',
                'price' => 149.99,
            ],
            [
                'name' => '4K Monitor 27"',
                'description' => 'Ultra HD 4K monitor with HDR support',
                'price' => 399.99,
            ],
            [
                'name' => 'USB-C Hub',
                'description' => 'Multi-port USB-C hub with HDMI, USB 3.0, and SD card reader',
                'price' => 49.99,
            ],
            [
                'name' => 'Webcam HD',
                'description' => '1080p HD webcam with auto-focus and noise cancellation',
                'price' => 79.99,
            ],
            [
                'name' => 'Standing Desk',
                'description' => 'Adjustable height standing desk with memory presets',
                'price' => 599.99,
            ],
            [
                'name' => 'Desk Chair Ergonomic',
                'description' => 'Comfortable ergonomic office chair with lumbar support',
                'price' => 299.99,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
