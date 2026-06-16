<?php

namespace App\Console\Commands;

use App\Enums\LegStatus;
use App\Enums\LegVisibility;
use App\Models\Aircraft;
use App\Models\Flight;
use App\Models\FlightLeg;
use Illuminate\Console\Command;

class ImportScheduleCommand extends Command
{
    protected $signature = 'import:schedule {file : Path to CSV file}';

    protected $description = 'Import flight legs from CSV (flight_name, origin, destination, departure_at, aircraft_tail, base_price, visibility)';

    public function handle(): int
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
            $flight = Flight::firstOrCreate(
                ['name' => $data['flight_name']],
                ['created_by' => 1],
            );

            $aircraft = Aircraft::where('tail_number', $data['aircraft_tail'] ?? '')->first();

            if (! $aircraft) {
                $this->warn('Skipping — aircraft not found: '.($data['aircraft_tail'] ?? ''));

                continue;
            }

            FlightLeg::create([
                'flight_id' => $flight->id,
                'aircraft_id' => $aircraft->id,
                'sort_order' => $count,
                'origin' => strtoupper($data['origin']),
                'destination' => strtoupper($data['destination']),
                'departure_at' => $data['departure_at'],
                'visibility' => $data['visibility'] ?? LegVisibility::Public,
                'status' => LegStatus::Available,
                'base_price' => $data['base_price'] ?? null,
            ]);

            $count++;
        }

        fclose($handle);
        $this->info("Imported {$count} legs.");

        return self::SUCCESS;
    }
}
