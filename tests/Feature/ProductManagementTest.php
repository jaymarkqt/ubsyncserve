<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a manager can create and edit an inventory product', function () {
    $productData = [
        'name' => 'Chicken Rice',
        'category' => 'Meals',
        'cost' => 50,
        'sellingPrice' => 85,
        'stock' => 12,
        'img' => '',
        'addOns' => [['name' => 'Extra rice', 'price' => 15]],
        'ingredients' => [['name' => 'Rice', 'stock' => 0]],
    ];

    $createdProduct = $this->postJson(route('products.store'), $productData)
        ->assertCreated()
        ->assertJsonPath('name', 'Chicken Rice')
        ->assertJsonPath('stock', 12)
        ->json();

    $updatedProductData = [
        ...$productData,
        'name' => 'Chicken Rice Special',
        'sellingPrice' => 99,
        'stock' => 18,
    ];

    $this->putJson(route('products.update', $createdProduct['id']), $updatedProductData)
        ->assertSuccessful()
        ->assertJsonPath('name', 'Chicken Rice Special')
        ->assertJsonPath('sellingPrice', 99)
        ->assertJsonPath('stock', 18);

    $this->assertDatabaseHas('products', [
        'id' => $createdProduct['id'],
        'name' => 'Chicken Rice Special',
        'selling_price' => 99,
        'stock_quantity' => 18,
    ]);
});
