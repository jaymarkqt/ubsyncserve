<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Burger Steak', 'category' => 'Lunch', 'image' => 'burgersteak.png'],
            ['name' => 'Beef Tapa', 'category' => 'Breakfast', 'image' => 'tapa.png'],
            ['name' => 'Iced Tea', 'category' => 'Drinks', 'image' => 'icetea.png'],
            ['name' => 'French Fries', 'category' => 'Snacks', 'image' => 'fries.png'],
            ['name' => 'Belgian Waffle', 'category' => 'Snacks', 'image' => 'belgian.png'],
            ['name' => 'French 75', 'category' => 'Drinks', 'image' => 'french75.png'],
            ['name' => 'Beef Tenderloin', 'category' => 'Dinner', 'image' => 'beef_tenderloin.png'],
            ['name' => 'Chicken', 'category' => 'Dinner', 'image' => 'chicken.png'],
            ['name' => 'Beef Broccoli', 'category' => 'Lunch', 'image' => 'beefbroccoli.png'],
        ];

        foreach ($products as $product) {
            $existingProduct = Product::query()
                ->where('image', $product['image'])
                ->orWhere('name', $product['name'])
                ->first();

            if ($existingProduct === null) {
                Product::create([
                    ...$product,
                    'cost_price' => 0,
                    'selling_price' => 0,
                    'stock_quantity' => 0,
                    'add_ons' => [],
                    'ingredients' => [],
                ]);
            }
        }
    }
}
