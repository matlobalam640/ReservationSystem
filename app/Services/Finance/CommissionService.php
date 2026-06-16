<?php

namespace App\Services\Finance;

use App\Enums\BookingChannel;
use App\Enums\CommissionStatus;
use App\Models\Booking;
use App\Models\CommissionLedger;
use App\Models\CommissionRule;

class CommissionService
{
    public function calculateForBooking(Booking $booking): ?CommissionLedger
    {
        if (! $booking->agency_id) {
            return null;
        }

        $rule = CommissionRule::query()
            ->where('is_active', true)
            ->where(function ($q) use ($booking) {
                $q->whereNull('agency_id')->orWhere('agency_id', $booking->agency_id);
            })
            ->where(function ($q) use ($booking) {
                $q->whereNull('channel')->orWhere('channel', $booking->booking_channel->value);
            })
            ->orderByDesc('priority')
            ->first();

        $heroAmount = $rule?->hero_amount ?? 0;
        $agencyAmount = $rule?->agency_amount ?? 0;

        return CommissionLedger::create([
            'booking_id' => $booking->id,
            'agency_id' => $booking->agency_id,
            'hero_amount' => $heroAmount,
            'agency_amount' => $agencyAmount,
            'status' => CommissionStatus::Pending,
        ]);
    }

    public function estimateForAgency(int $agencyId, BookingChannel $channel = BookingChannel::Agency): array
    {
        $rule = CommissionRule::query()
            ->where('is_active', true)
            ->where(function ($q) use ($agencyId) {
                $q->whereNull('agency_id')->orWhere('agency_id', $agencyId);
            })
            ->where(function ($q) use ($channel) {
                $q->whereNull('channel')->orWhere('channel', $channel->value);
            })
            ->orderByDesc('priority')
            ->first();

        return [
            'hero_amount' => (float) ($rule?->hero_amount ?? 0),
            'agency_amount' => (float) ($rule?->agency_amount ?? 0),
            'rule_name' => $rule?->name,
        ];
    }
}
