<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReservationEmailController extends Controller
{
    public function confirmEmail(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => ['required', 'string'],
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'date' => ['required', 'string'],
            'time' => ['required', 'string'],
            'type' => ['required', 'string'],
            'table' => ['nullable', 'string'],
        ]);

        // I-format ang oras para maging 1:23 AM / PM
        $formattedTime = date('g:i A', strtotime($data['time']));

        // I-set ang pangalan ng system
        $appName = 'UBSYNCSERVE';

        try {
            $booking = DB::table('customer_bookings')
                ->where('booking_reference', $data['id'])
                ->whereNull('archived_at')
                ->first();

            if ($booking) {
                $claimed = DB::table('customer_bookings')
                    ->where('id', $booking->id)
                    ->whereNull('confirmation_email_sent_at')
                    ->update([
                        'confirmation_email_sent_at' => now(),
                        'updated_at' => now(),
                    ]);

                if ($claimed === 0) {
                    return response()->json(['success' => true, 'already_sent' => true]);
                }
            }

            $selectTablesUrl = route('order.select-tables').'?type='
                .urlencode($data['type']).'&resId='.urlencode($data['id']);

            Mail::mailer('smtp')->raw(
                "Hello {$data['name']},\n\n".
                "Your reservation has been confirmed.\n".
                'Type: '.strtoupper(str_replace('-', ' ', $data['type']))."\n".
                (! empty($data['table']) ? "Table: {$data['table']}\n" : '').
                "Visit Date: {$data['date']}\n".
                "Visit Time: {$formattedTime}\n\n".
                "Please select your table using this link:\n{$selectTablesUrl}\n\n".
                "Thank you for booking with {$appName}.\n",
                function ($message) use ($data, $appName) {
                    $message->to($data['email'])
                        ->subject("Reservation Confirmed - {$appName}")
                        ->from(config('mail.from.address', 'no-reply@ubsync.com'), $appName);
                }
            );

            return response()->json(['success' => true]);
        } catch (\Throwable $exception) {
            Log::error('Reservation confirmation email failed.', [
                'reservation_id' => $data['id'],
                'recipient' => $data['email'],
                'error' => $exception->getMessage(),
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'The reservation was confirmed, but the email could not be sent. Please check the mail server configuration.',
            ], 500);
        }
    }
}
