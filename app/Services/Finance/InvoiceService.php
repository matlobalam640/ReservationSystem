<?php

namespace App\Services\Finance;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class InvoiceService
{
    public function createFromBooking(Booking $booking): Invoice
    {
        $invoice = Invoice::create([
            'invoice_number' => 'INV-'.strtoupper(Str::random(8)),
            'invoice_type' => InvoiceType::Passenger,
            'booking_id' => $booking->id,
            'agency_id' => $booking->agency_id,
            'subtotal' => $booking->total_amount,
            'total' => $booking->total_amount,
            'status' => InvoiceStatus::Unpaid,
            'due_date' => now()->addDays(7),
        ]);

        $invoice->lines()->create([
            'description' => 'Flight booking '.$booking->reference_number,
            'amount' => $booking->flightLeg->base_price ?? $booking->total_amount,
        ]);

        foreach ($booking->bookingAddOns as $addOn) {
            $invoice->lines()->create([
                'description' => $addOn->addOn->name,
                'amount' => $addOn->total_price,
            ]);
        }

        return $invoice;
    }

    public function recordPayment(Invoice $invoice, float $amount, PaymentMethod $method, ?int $userId = null): Payment
    {
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'payment_method' => $method,
            'amount' => $amount,
            'paid_at' => now(),
            'recorded_by' => $userId,
        ]);

        $paid = $invoice->payments()->sum('amount');
        $invoice->update([
            'status' => $paid >= $invoice->total ? InvoiceStatus::Paid : InvoiceStatus::Partial,
        ]);

        if ($invoice->booking) {
            $invoice->booking->update([
                'payment_status' => $paid >= $invoice->total ? PaymentStatus::Paid : PaymentStatus::Partial,
            ]);
        }

        return $payment;
    }

    public function generatePdf(Invoice $invoice)
    {
        $invoice->load(['lines', 'booking.flightLeg']);

        return Pdf::loadView('pdf.invoice', compact('invoice'));
    }
}
