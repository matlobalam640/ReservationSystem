<?php

namespace App\Filament\Resources\Passengers\Pages;

use App\Filament\Resources\Passengers\PassengerResource;
use App\Models\FlightLeg;
use App\Models\Seat;
use App\Services\Booking\PassengerMoveService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use InvalidArgumentException;

class EditPassenger extends EditRecord
{
    protected static string $resource = PassengerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('movePassenger')
                ->label('Move to Another Leg')
                ->icon('heroicon-o-arrow-right-circle')
                ->form([
                    Select::make('flight_leg_id')
                        ->label('Target Leg')
                        ->options(fn () => FlightLeg::query()
                            ->where('departure_at', '>', now())
                            ->orderBy('departure_at')
                            ->get()
                            ->mapWithKeys(fn ($l) => [$l->id => $l->routeLabel().' — '.$l->departure_at->format('M j g:i A')])
                            ->all())
                        ->required()
                        ->live(),
                    Select::make('seat_id')
                        ->label('Seat')
                        ->options(fn (callable $get) => $get('flight_leg_id')
                            ? Seat::query()
                                ->where('flight_leg_id', $get('flight_leg_id'))
                                ->where('is_available', true)
                                ->pluck('seat_number', 'id')
                                ->all()
                            : [])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $bp = $this->record->bookingPassengers()->latest()->first();
                    if (! $bp) {
                        return;
                    }

                    try {
                        app(PassengerMoveService::class)->moveToLeg(
                            $bp,
                            FlightLeg::findOrFail($data['flight_leg_id']),
                            (int) $data['seat_id'],
                        );
                    } catch (InvalidArgumentException $e) {
                        Notification::make()->danger()->body($e->getMessage())->send();

                        return;
                    }

                    Notification::make()->success()->body('Passenger moved successfully.')->send();
                })
                ->visible(fn () => $this->record->bookingPassengers()->exists()),
        ];
    }
}
