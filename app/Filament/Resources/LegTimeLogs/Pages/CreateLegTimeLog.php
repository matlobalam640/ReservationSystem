<?php

namespace App\Filament\Resources\LegTimeLogs\Pages;

use App\Filament\Resources\LegTimeLogs\LegTimeLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLegTimeLog extends CreateRecord
{
    protected static string $resource = LegTimeLogResource::class;

    public function mount(): void
    {
        parent::mount();

        $legId = request()->integer('flight_leg_id');

        if ($legId > 0) {
            $this->form->fill(['flight_leg_id' => $legId]);
        }
    }
}
