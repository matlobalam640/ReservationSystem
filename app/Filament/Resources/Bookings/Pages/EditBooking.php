<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use App\Models\FlightLeg;
use App\Models\Seat;
use App\Services\Booking\BookingService;
use App\Services\Booking\PassengerMoveService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use InvalidArgumentException;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('rebook')
                ->label('Rebook')
                ->icon('heroicon-o-arrow-path')
                ->form([
                    Select::make('flight_leg_id')
                        ->label('New Leg')
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
                            ? Seat::query()->where('flight_leg_id', $get('flight_leg_id'))->where('is_available', true)->pluck('seat_number', 'id')->all()
                            : [])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    try {
                        $newBooking = app(PassengerMoveService::class)->rebook(
                            $this->record,
                            FlightLeg::findOrFail($data['flight_leg_id']),
                            (int) $data['seat_id'],
                        );
                        $this->redirect(BookingResource::getUrl('edit', ['record' => $newBooking]));
                    } catch (InvalidArgumentException $e) {
                        Notification::make()->danger()->body($e->getMessage())->send();
                    }
                })
                ->visible(fn () => $this->record->status->value !== 'cancelled'),
            Action::make('cancel')
                ->label('Cancel Booking')
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn () => app(BookingService::class)->cancelBooking($this->record))
                ->visible(fn () => $this->record->status->value !== 'cancelled'),
            Action::make('noShow')
                ->label('Mark No-Show')
                ->color('warning')
                ->requiresConfirmation()
                ->action(fn () => app(BookingService::class)->markNoShow($this->record)),
            DeleteAction::make(),
        ];
    }
}
