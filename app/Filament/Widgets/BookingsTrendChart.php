<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;

class BookingsTrendChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Booking activity';

    protected ?string $description = 'New reservations over the last 14 days';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 4,
    ];

    protected ?string $maxHeight = '320px';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $labels = [];
        $counts = [];
        $revenue = [];

        foreach (range(13, 0) as $daysAgo) {
            $date = today()->subDays($daysAgo);
            $labels[] = $date->format('M j');
            $counts[] = Booking::query()->whereDate('created_at', $date)->count();
            $revenue[] = (float) Booking::query()->whereDate('created_at', $date)->sum('total_amount');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Bookings',
                    'data' => $counts,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.12)',
                    'fill' => true,
                    'tension' => 0.35,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Revenue ($)',
                    'data' => $revenue,
                    'borderColor' => '#0f172a',
                    'backgroundColor' => 'rgba(15, 23, 42, 0.06)',
                    'fill' => true,
                    'tension' => 0.35,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'position' => 'left',
                    'ticks' => ['precision' => 0],
                ],
                'y1' => [
                    'beginAtZero' => true,
                    'position' => 'right',
                    'grid' => ['drawOnChartArea' => false],
                ],
            ],
            'plugins' => [
                'legend' => ['display' => true],
            ],
        ];
    }
}
