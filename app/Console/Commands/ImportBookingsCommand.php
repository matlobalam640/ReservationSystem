<?php

namespace App\Console\Commands;

use App\Enums\BookingChannel;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\FlightLeg;
use App\Services\Booking\BookingService;
use Illuminate\Console\Command;

class ImportBookingsCommand extends Command
{
    protected $signature = 'import:bookings {file : Path to CSV file}';

    protected $description = 'Import bookings from CSV (flight_leg_id, first_name, last_name, email, paid, payment_method, seat_id)';

    public function handle(BookingService $bookingService): int
    {
        $path = $this->argument('file');

        if (! file_exists($path)) {
            $this->error('File not found: '.$path);

            return self::FAILURE;
        }

        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);
        $count = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            $leg = FlightLeg::find($data['flight_leg_id'] ?? null);

            if (! $leg) {
                $this->warn('Skipping row — invalid leg');

                continue;
            }

            $booking = $bookingService->createBooking(
                $leg,
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'] ?? null,
                    'weight_kg' => $data['weight_kg'] ?? 75,
                ],
                (int) ($data['seat_id'] ?? $leg->seats()->where('is_available', true)->value('id')),
                BookingChannel::Admin,
            );

            if (($data['paid'] ?? '') === '1') {
                $booking->update(['payment_status' => PaymentStatus::Paid]);
            }

            $count++;
        }

        fclose($handle);
        $this->info("Imported {$count} bookings.");

        return self::SUCCESS;
    }
}
