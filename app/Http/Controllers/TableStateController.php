<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TableStateController extends Controller
{
    public function index(): JsonResponse
    {
        $now = now();

        DB::table('restaurant_tables')->insertOrIgnore(
            collect(range(1, 15))
                ->map(fn (int $tableNumber): array => [
                    'table_number' => $tableNumber,
                    'status' => 'available',
                    'orders' => json_encode([]),
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
                ->all()
        );

        $tables = DB::table('restaurant_tables')
            ->orderBy('table_number')
            ->get()
            ->map(fn (object $table): array => [
                'id' => (int) $table->table_number,
                'status' => $table->status,
                'isPaid' => (bool) $table->is_paid,
                'adults' => (int) $table->adults,
                'children' => (int) $table->children,
                'guests' => (int) $table->guests,
                'bill' => (float) $table->bill,
                'orders' => json_decode($table->orders, true, flags: JSON_THROW_ON_ERROR),
                'startTime' => $table->start_time,
            ]);

        return response()->json($tables);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tables' => ['required', 'array', 'min:1', 'max:15'],
            'tables.*.id' => ['required', 'integer', 'between:1,15', 'distinct'],
            'tables.*.status' => ['required', Rule::in([
                'available',
                'occupied',
                'paid',
                'reserved-advance',
                'reserved-booking',
            ])],
            'tables.*.isPaid' => ['sometimes', 'boolean'],
            'tables.*.adults' => ['sometimes', 'integer', 'min:0'],
            'tables.*.children' => ['sometimes', 'integer', 'min:0'],
            'tables.*.guests' => ['sometimes', 'integer', 'min:0'],
            'tables.*.bill' => ['sometimes', 'numeric', 'min:0'],
            'tables.*.orders' => ['sometimes', 'array'],
            'tables.*.startTime' => ['sometimes', 'nullable', 'date'],
        ]);

        DB::transaction(function () use ($validated): void {
            foreach ($validated['tables'] as $table) {
                DB::table('restaurant_tables')->insertOrIgnore([
                    'table_number' => $table['id'],
                    'status' => 'available',
                    'orders' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $attributes = [
                    'status' => $table['status'],
                    'updated_at' => now(),
                ];

                foreach ([
                    'isPaid' => 'is_paid',
                    'adults' => 'adults',
                    'children' => 'children',
                    'guests' => 'guests',
                    'bill' => 'bill',
                ] as $input => $column) {
                    if (array_key_exists($input, $table)) {
                        $attributes[$column] = $input === 'isPaid'
                            ? (bool) $table[$input]
                            : $table[$input];
                    }
                }

                if (array_key_exists('orders', $table)) {
                    $attributes['orders'] = json_encode($table['orders'], JSON_THROW_ON_ERROR);
                }

                if (array_key_exists('startTime', $table)) {
                    $attributes['start_time'] = $table['startTime'] === null
                        ? null
                        : Carbon::parse($table['startTime'])->toDateTimeString();
                }

                DB::table('restaurant_tables')
                    ->where('table_number', $table['id'])
                    ->update($attributes);
            }
        });

        return response()->json(['success' => true]);
    }

    public function clear(int $tableNumber): JsonResponse
    {
        $updatedRows = DB::table('restaurant_tables')
            ->where('table_number', $tableNumber)
            ->update([
                'status' => 'available',
                'is_paid' => false,
                'adults' => 0,
                'children' => 0,
                'guests' => 0,
                'bill' => 0,
                'orders' => json_encode([], JSON_THROW_ON_ERROR),
                'start_time' => null,
                'updated_at' => now(),
            ]);

        if (
            $updatedRows === 0
            && ! DB::table('restaurant_tables')->where('table_number', $tableNumber)->exists()
        ) {
            return response()->json(['message' => 'Table not found.'], 404);
        }

        return response()->json(['success' => true]);
    }
}
