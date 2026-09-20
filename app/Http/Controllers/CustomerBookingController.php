<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CustomerBookingController extends Controller
{
    public function index(): JsonResponse
    {
        $bookings = DB::table('customer_bookings')
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereNull('archived_at')
            ->latest()
            ->get()
            ->map(fn (object $booking): array => [
                'id' => $booking->booking_reference,
                'name' => $booking->name,
                'email' => $booking->email,
                'phone' => $booking->phone,
                'type' => $booking->type,
                'adults' => $booking->adults,
                'children' => $booking->children,
                'guests' => $booking->guests,
                'date' => $booking->booking_date,
                'time' => substr($booking->booking_time, 0, 5),
                'requests' => $booking->requests,
                'status' => $booking->status,
                'createdAt' => $booking->created_at,
            ]);

        return response()->json($bookings);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['advance-order', 'table-reservation'])],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'adults' => ['required', 'integer', 'min:0'],
            'children' => ['required', 'integer', 'min:0'],
            'requests' => ['nullable', 'string', 'max:2000'],
        ]);

        $guests = $validated['adults'] + $validated['children'];

        if ($guests < 1) {
            return response()->json([
                'message' => 'At least one guest is required.',
            ], 422);
        }

        $booking = DB::table('customer_bookings')->insertGetId([
            'booking_reference' => 'RES-'.strtoupper(Str::random(8)),
            'type' => $validated['type'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'adults' => $validated['adults'],
            'children' => $validated['children'],
            'guests' => $guests,
            'booking_date' => $validated['date'],
            'booking_time' => $validated['time'],
            'requests' => $validated['requests'] ?? null,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Reservation request submitted successfully. Please wait for confirmation.',
            'booking_id' => $booking,
        ], 201);
    }

    public function updateStatus(Request $request, string $bookingReference): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['confirmed', 'cancelled'])],
        ]);

        $updated = DB::table('customer_bookings')
            ->where('booking_reference', $bookingReference)
            ->whereNull('archived_at')
            ->where('status', 'pending')
            ->update([
                'status' => $validated['status'],
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            $booking = DB::table('customer_bookings')
                ->where('booking_reference', $bookingReference)
                ->first();

            if (! $booking) {
                return response()->json(['message' => 'Reservation not found.'], 404);
            }

            return response()->json([
                'message' => 'Reservation has already been processed.',
                'status' => $booking->status,
            ], 409);
        }

        return response()->json(['success' => true, 'status' => $validated['status']]);
    }

    public function archive(string $bookingReference): JsonResponse
    {
        $updated = DB::table('customer_bookings')
            ->where('booking_reference', $bookingReference)
            ->whereNull('archived_at')
            ->update([
                'archived_at' => now(),
                'updated_at' => now(),
            ]);

        return $updated > 0
            ? response()->json(['success' => true])
            : response()->json(['message' => 'Reservation not found.'], 404);
    }

    public function archiveAll(): JsonResponse
    {
        DB::table('customer_bookings')
            ->whereNull('archived_at')
            ->whereIn('status', ['pending', 'confirmed', 'cancelled'])
            ->update([
                'archived_at' => now(),
                'updated_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }
}
