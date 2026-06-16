<?php

namespace App\Services\Finance;

use App\Models\HourUsageLog;
use App\Models\PrepaidHourAccount;

class PrepaidHourService
{
    public function useHours(
        PrepaidHourAccount $account,
        float $hours,
        string $usageType = 'flight',
        ?int $flightLegId = null,
        ?string $notes = null,
    ): HourUsageLog {
        if ($hours > $account->hoursRemaining()) {
            throw new \InvalidArgumentException('Insufficient prepaid hours.');
        }

        $log = $account->usageLogs()->create([
            'flight_leg_id' => $flightLegId,
            'usage_type' => $usageType,
            'hours' => $hours,
            'notes' => $notes,
        ]);

        $account->update([
            'hours_used' => (float) $account->hours_used + $hours,
        ]);

        return $log;
    }

    public function purchaseHours(PrepaidHourAccount $account, float $hours): void
    {
        $account->update([
            'hours_purchased' => (float) $account->hours_purchased + $hours,
        ]);
    }
}
