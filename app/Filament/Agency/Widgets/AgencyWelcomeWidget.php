<?php

namespace App\Filament\Agency\Widgets;

use Filament\Widgets\Widget;

class AgencyWelcomeWidget extends Widget
{
    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.agency.widgets.agency-welcome';

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $user = auth()->user();
        $agency = $user?->agency;

        return [
            'userName' => $user?->name ?? 'Agency User',
            'agencyName' => $agency?->name ?? 'Your Agency',
            'paymentModel' => $agency?->payment_model?->label(),
        ];
    }
}
