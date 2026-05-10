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

        try {
            Mail::raw(
                "Hello {$data['name']},\n\n" .
                "Your reservation has been confirmed.\n" .
                "Type: " . strtoupper($data['type']) . "\n" .
                ($data['table'] ? "Table: {$data['table']}\n" : '') .
                "Visit Date: {$data['date']}\n" .
                "Visit Time: {$data['time']}\n\n" .
                "Thank you for booking with " . config('app.name', 'UB Sync') . ".\n" .
                "You can review your reservation here: " . url(route('order.select-tables')) . "\n",
                function ($message) use ($data) {
                    $message->to($data['email'])
                            ->subject('Reservation Confirmed - ' . config('app.name', 'UB Sync'))
                            ->from(config('mail.from.address', 'no-reply@ubsync.com'), config('mail.from.name', config('app.name', 'UB Sync')));
                }
            );

            return response()->json(['success' => true]);
        } catch (\Throwable $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }
}
