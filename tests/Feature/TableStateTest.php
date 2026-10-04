<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('table statuses are stored centrally and returned in the floorplan format', function () {
    $this->getJson(route('tables.index'))
        ->assertSuccessful()
        ->assertJsonCount(15)
        ->assertJsonPath('0.status', 'available');

    $this->putJson(route('tables.update'), [
        'tables' => [
            [
                'id' => 2,
                'status' => 'occupied',
                'isPaid' => false,
                'adults' => 2,
                'children' => 1,
                'guests' => 3,
                'bill' => 450,
                'orders' => [['name' => 'Dinner', 'qty' => 1, 'price' => 450]],
                'startTime' => now()->toISOString(),
            ],
        ],
    ])->assertSuccessful();

    $this->getJson(route('tables.index'))
        ->assertSuccessful()
        ->assertJsonPath('1.status', 'occupied')
        ->assertJsonPath('1.guests', 3)
        ->assertJsonPath('1.orders.0.name', 'Dinner');
});

test('table status updates reject invalid statuses and duplicate table numbers', function () {
    $this->putJson(route('tables.update'), [
        'tables' => [
            ['id' => 1, 'status' => 'closed'],
        ],
    ])->assertUnprocessable();

    $this->putJson(route('tables.update'), [
        'tables' => [
            ['id' => 1, 'status' => 'occupied'],
            ['id' => 1, 'status' => 'available'],
        ],
    ])->assertUnprocessable();
});

test('paid table can be cleared with a single table-specific request', function () {
    $this->getJson(route('tables.index'))->assertSuccessful();
    $this->putJson(route('tables.update'), [
        'tables' => [
            [
                'id' => 1,
                'status' => 'paid',
                'isPaid' => true,
                'adults' => 2,
                'children' => 1,
                'guests' => 3,
                'bill' => 450,
                'orders' => [['name' => 'Dinner', 'qty' => 1, 'price' => 450]],
                'startTime' => now()->toISOString(),
            ],
        ],
    ])->assertSuccessful();

    $this->postJson(route('tables.clear', ['tableNumber' => 1]))
        ->assertSuccessful()
        ->assertJsonPath('success', true);

    $this->getJson(route('tables.index'))
        ->assertSuccessful()
        ->assertJsonPath('0.status', 'available')
        ->assertJsonPath('0.isPaid', false)
        ->assertJsonPath('0.guests', 0)
        ->assertJsonPath('0.bill', 0)
        ->assertJsonPath('0.orders', [])
        ->assertJsonPath('0.startTime', null);
});

test('all floorplan statuses can be synchronized', function (string $status) {
    $this->putJson(route('tables.update'), [
        'tables' => [
            ['id' => 3, 'status' => $status],
        ],
    ])->assertSuccessful();

    $this->getJson(route('tables.index'))
        ->assertSuccessful()
        ->assertJsonPath('2.status', $status);
})->with([
    'occupied' => 'occupied',
    'advance order' => 'reserved-advance',
    'reserved table' => 'reserved-booking',
    'paid' => 'paid',
]);
