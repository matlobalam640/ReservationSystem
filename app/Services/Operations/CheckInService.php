<?php

namespace App\Services\Operations;

use App\Enums\BookingStatus;
use App\Enums\TicketStatus;
use App\Models\BookingPassenger;
use App\Services\Finance\InvoiceService;

class CheckInService
{
    public function checkIn(BookingPassenger $bp, float $actualWeight, ?float $actualBaggage = null): void
    {
        $bp->update([
            'actual_weight_kg' => $actualWeight,
            'actual_baggage_weight_kg' => $actualBaggage,
            'checked_in_at' => now(),
            'ticket_status' => TicketStatus::CheckedIn,
        ]);

        $bp->booking->update(['status' => BookingStatus::CheckedIn]);

        $this->applyOverweightFeeIfNeeded($bp, $actualWeight, $actualBaggage);
    }

    private function applyOverweightFeeIfNeeded(BookingPassenger $bp, float $actualWeight, ?float $actualBaggage): void
    {
        $estimated = (float) ($bp->weight_kg ?? 0) + (float) ($bp->baggage_weight_kg ?? 0);
        $actual = $actualWeight + (float) ($actualBaggage ?? 0);
        $excess = $actual - $estimated;

        if ($excess <= 5) {
            return;
        }

        $fee = round($excess * 2, 2);
        $booking = $bp->booking;
        $booking->update(['total_amount' => (float) $booking->total_amount + $fee]);

        $invoice = $booking->invoices()->latest()->first();
        if ($invoice) {
            $invoice->lines()->create([
                'description' => 'Overweight fee at check-in ('.number_format($excess, 1).' kg excess)',
                'amount' => $fee,
            ]);
            $invoice->update([
                'subtotal' => (float) $invoice->subtotal + $fee,
                'total' => (float) $invoice->total + $fee,
            ]);
        } else {
            app(InvoiceService::class)->createFromBooking($booking->fresh());
        }
    }
}
