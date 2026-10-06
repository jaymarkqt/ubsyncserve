<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('waiter orders are saved and decrement product stock', function () {
    $product = Product::create([
        'name' => 'Burger Steak',
        'category' => 'Lunch',
        'selling_price' => 100,
        'stock_quantity' => 5,
        'add_ons' => [['name' => 'Cheese', 'price' => 15]],
    ]);

    $response = $this->postJson(route('orders.complete'), [
        'table_number' => '4',
        'items' => [
            [
                'product_id' => $product->id,
                'quantity' => 2,
                'add_ons' => [['name' => 'Cheese', 'price' => 0]],
            ],
        ],
    ]);

    $response->assertCreated()
        ->assertJsonPath('total_amount', 241.5)
        ->assertJsonPath('subtotal_amount', 230)
        ->assertJsonPath('discount_amount', 0)
        ->assertJsonPath('vat_amount', 11.5)
        ->assertJsonPath('grand_total_amount', 241.5);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'stock_quantity' => 3,
    ]);
    $this->assertDatabaseHas('orders', [
        'order_number' => $response->json('order_number'),
        'table_number' => '4',
        'total_amount' => 241.5,
    ]);
    $this->assertDatabaseHas('order_items', [
        'product_id' => $product->id,
        'product_name' => 'Burger Steak',
        'quantity' => 2,
        'unit_price' => 115,
    ]);
    $this->assertDatabaseHas('restaurant_tables', [
        'table_number' => 4,
        'status' => 'occupied',
        'bill' => 230,
    ]);

    $tableState = DB::table('restaurant_tables')->where('table_number', 4)->first();
    expect(json_decode($tableState->orders, true))
        ->toBe([[
            'name' => 'Burger Steak',
            'qty' => 2,
            'price' => 115,
            'addonName' => 'Cheese',
        ]]);
});

test('senior and pwd discounts apply immediately without an ID number', function (string $discountType) {
    $product = Product::create([
        'name' => 'Rice Bowl',
        'selling_price' => 100,
        'stock_quantity' => 5,
    ]);

    $response = $this->postJson(route('orders.complete'), [
        'table_number' => '4',
        'discount_type' => $discountType,
        'items' => [
            ['product_id' => $product->id, 'quantity' => 1],
        ],
    ]);

    $response->assertCreated()
        ->assertJsonPath('subtotal_amount', 100)
        ->assertJsonPath('discount_amount', 20)
        ->assertJsonPath('vat_amount', 4)
        ->assertJsonPath('grand_total_amount', 84)
        ->assertJsonPath('total_amount', 84);

    $this->assertDatabaseHas('orders', [
        'order_number' => $response->json('order_number'),
        'total_amount' => 84,
    ]);

    $this->assertDatabaseHas('restaurant_tables', [
        'table_number' => 4,
        'bill' => 80,
    ]);

    $this->getJson(route('tables.index'))
        ->assertSuccessful()
        ->assertJsonPath('3.bill', 80)
        ->assertJsonPath('3.orders.0.price', 100)
        ->assertJsonPath('3.orders.0.discountType', $discountType)
        ->assertJsonPath('3.orders.0.discountAmount', 20);
})->with(['senior', 'pwd']);

test('orders store no separate discount or ID fields', function () {
    foreach ([
        'subtotal_amount',
        'discount_type',
        'discount_id_image_path',
        'discount_amount',
        'vat_amount',
        'grand_total_amount',
    ] as $column) {
        expect(Schema::hasColumn('orders', $column))->toBeFalse();
    }
});

test('an order that exceeds available stock is not saved', function () {
    $product = Product::create([
        'name' => 'Beef Tapa',
        'selling_price' => 120,
        'stock_quantity' => 3,
    ]);

    $this->postJson(route('orders.complete'), [
        'items' => [
            ['product_id' => $product->id, 'quantity' => 2],
            ['product_id' => $product->id, 'quantity' => 2],
        ],
    ])->assertUnprocessable();

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'stock_quantity' => 3,
    ]);
    $this->assertDatabaseCount('orders', 0);
});

test('advance orders keep their reserved status and guest count when sent to stations', function () {
    $product = Product::create([
        'name' => 'Chicken Meal',
        'selling_price' => 150,
        'stock_quantity' => 3,
    ]);

    DB::table('restaurant_tables')->insert([
        'table_number' => 6,
        'status' => 'reserved-advance',
        'adults' => 2,
        'children' => 2,
        'guests' => 4,
        'bill' => 300,
        'orders' => json_encode([]),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->postJson(route('orders.complete'), [
        'table_number' => '6',
        'adults' => 0,
        'children' => 0,
        'items' => [
            ['product_id' => $product->id, 'quantity' => 1],
        ],
    ])->assertCreated();

    $this->assertDatabaseHas('restaurant_tables', [
        'table_number' => 6,
        'status' => 'reserved-advance',
        'guests' => 4,
        'bill' => 450,
    ]);
});

test('the first catalog request creates products for every food image when inventory is empty', function () {
    $this->getJson(route('products.index'))
        ->assertSuccessful()
        ->assertJsonCount(9);

    $this->assertDatabaseCount('products', 9);
});
