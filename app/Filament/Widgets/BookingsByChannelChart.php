<?php

namespace App\Filament\Widgets;

use App\Enums\BookingChannel;
use App\Models\Booking;
use Filament\Widgets\ChartWidget;

class BookingsByChannelChart extends ChartWidget
{
    protected static ?int $sort = 4;

    protected ?string $heading = 'Bookings by channel';

    protected ?string $description = 'How passengers are reserving flights';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 2,
    ];

    protected ?string $maxHeight = '320px';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $counts = Booking::query()
            ->selectRaw('booking_channel, COUNT(*) as total')
            ->groupBy('booking_channel')
            ->pluck('total', 'booking_channel');

        $labels = [];
        $data = [];

        foreach (BookingChannel::cases() as $channel) {
            $total = (int) ($counts[$channel->value] ?? 0);

            if ($total === 0) {
                continue;
            }

            $labels[] = $channel->label();
            $data[] = $total;
        }

        if ($labels === []) {
            $labels = ['No bookings yet'];
            $data = [1];
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => [
                        '#f59e0b',
                        '#0f172a',
                        '#3b82f6',
                        '#10b981',
                        '#8b5cf6',
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
