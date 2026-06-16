<?php

namespace App\Services\Finance;

use App\Models\CreditAccount;
use App\Models\CreditAccountEntry;
use App\Models\Invoice;
use Carbon\Carbon;

class CreditAccountService
{
    public function chargeBooking(CreditAccount $account, float $amount, ?int $bookingId = null, ?string $description = null): CreditAccountEntry
    {
        $entry = $account->entries()->create([
            'booking_id' => $bookingId,
            'type' => 'charge',
            'amount' => $amount,
            'description' => $description ?? 'Booking charge',
        ]);

        $account->update(['balance' => (float) $account->balance + $amount]);

        return $entry;
    }

    public function generateMonthlyStatement(CreditAccount $account, Carbon $month): Invoice
    {
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        $charges = $account->entries()
            ->where('type', 'charge')
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        $interest = 0.0;
        if ((float) $account->balance > 0 && (float) $account->interest_rate > 0) {
            $interest = round((float) $account->balance * ((float) $account->interest_rate / 100), 2);
        }

        $total = round((float) $charges + $interest, 2);

        $invoice = Invoice::create([
            'invoice_number' => 'CR-'.$account->account_number.'-'.$month->format('Ym'),
            'invoice_type' => \App\Enums\InvoiceType::Agency,
            'subtotal' => $charges,
            'tax' => $interest,
            'total' => $total,
            'status' => \App\Enums\InvoiceStatus::Unpaid,
            'due_date' => $end->copy()->addDays(30),
        ]);

        $invoice->lines()->create([
            'description' => "Monthly charges for {$month->format('F Y')}",
            'amount' => $charges,
        ]);

        if ($interest > 0) {
            $invoice->lines()->create([
                'description' => 'Interest on overdue balance',
                'amount' => $interest,
            ]);
        }

        return $invoice;
    }
}
