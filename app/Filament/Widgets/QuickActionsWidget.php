<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ReconciliationDashboard;
use App\Filament\Pages\WalkInCheckout;
use App\Filament\Resources\Bookings\BookingResource;
use App\Filament\Resources\Flights\FlightResource;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.widgets.quick-actions';

    /**
     * @return array<int, array{label: string, description: string, url: string, icon: string, color: string}>
     */
    protected function getActions(): array
    {
        return [
            [
                'label' => 'New Booking',
                'description' => 'Create a reservation',
                'url' => BookingResource::getUrl('create'),
                'icon' => 'ticket',
                'color' => 'amber',
            ],
            [
                'label' => 'Walk-In Checkout',
                'description' => 'Record payment',
                'url' => WalkInCheckout::getUrl(),
                'icon' => 'credit-card',
                'color' => 'slate',
            ],
            [
                'label' => 'Check-In',
                'description' => 'Passenger check-in',
                'url' => route('check-in.index'),
                'icon' => 'clipboard',
                'color' => 'blue',
            ],
            [
                'label' => 'Manifest',
                'description' => 'Download PDF',
                'url' => route('manifest.index'),
                'icon' => 'document',
                'color' => 'emerald',
            ],
            [
                'label' => 'Flights',
                'description' => 'Schedules & legs',
                'url' => FlightResource::getUrl('index'),
                'icon' => 'map',
                'color' => 'violet',
            ],
            [
                'label' => 'Reconciliation',
                'description' => 'Operator costs',
                'url' => ReconciliationDashboard::getUrl(),
                'icon' => 'scale',
                'color' => 'rose',
            ],
            [
                'label' => 'Public Site',
                'description' => 'View booking page',
                'url' => route('home'),
                'icon' => 'globe',
                'color' => 'cyan',
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
