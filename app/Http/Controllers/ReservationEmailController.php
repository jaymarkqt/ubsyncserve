<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReservationEmailController extends Controller
{
    public function confirmEmail(Request $request)
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
            Mail::raw(
                "Hello {$data['name']},\n\n" .
                "Your reservation has been confirmed.\n" .
                "Type: " . strtoupper($data['type']) . "\n" .
                ($data['table'] ? "Table: {$data['table']}\n" : '') .
                "Visit Date: {$data['date']}\n" .
                "Visit Time: {$formattedTime}\n\n" .
                "Thank you for booking with {$appName}.\n" .
                "You can review your reservation here: " . url(route('order.select-tables')) . "\n",
                function ($message) use ($data, $appName) {
                    $message->to($data['email'])
                            ->subject("Reservation Confirmed - {$appName}")
                            ->from(config('mail.from.address', 'no-reply@ubsync.com'), $appName);
                }
            );

            return response()->json(['success' => true]);
        } catch (\Throwable $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }
}