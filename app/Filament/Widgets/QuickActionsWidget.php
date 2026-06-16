<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ReconciliationDashboard;
use App\Filament\Pages\WalkInCheckout;
use App\Filament\Resources\Bookings\BookingResource;
use App\Filament\Resources\Flights\FlightResource;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.widgets.quick-actions';

    /**
     * @return array<int, array{label: string, description: string, url: string, icon: string, primary?: bool}>
     */
    protected function getActions(): array
    {
        return [
            [
                'label' => 'New Booking',
                'description' => 'Create a reservation',
                'url' => BookingResource::getUrl('create'),
                'icon' => Heroicon::OutlinedTicket,
                'primary' => true,
            ],
            [
                'label' => 'Walk-In Checkout',
                'description' => 'Record payment',
                'url' => WalkInCheckout::getUrl(),
                'icon' => Heroicon::OutlinedCreditCard,
            ],
            [
                'label' => 'Check-In',
                'description' => 'Passenger check-in',
                'url' => route('check-in.index'),
                'icon' => Heroicon::OutlinedClipboardDocumentCheck,
            ],
            [
                'label' => 'Manifest',
                'description' => 'Download PDF',
                'url' => route('manifest.index'),
                'icon' => Heroicon::OutlinedDocumentText,
            ],
            [
                'label' => 'Flights',
                'description' => 'Schedules & legs',
                'url' => FlightResource::getUrl('index'),
                'icon' => Heroicon::OutlinedMap,
            ],
            [
                'label' => 'Reconciliation',
                'description' => 'Operator costs',
                'url' => ReconciliationDashboard::getUrl(),
                'icon' => Heroicon::OutlinedScale,
            ],
            [
                'label' => 'Public Site',
                'description' => 'View booking page',
                'url' => route('home'),
                'icon' => Heroicon::OutlinedGlobeAlt,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'actions' => $this->getActions(),
        ];
    }
}
