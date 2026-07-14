<?php

namespace Database\Seeders;

use App\Models\Bed;
use Illuminate\Database\Seeder;

class BedSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            1 => 'standard',
            2 => 'standard',
            3 => 'standard',
            4 => 'standard',
            5 => 'standard',
            6 => 'électrique',
            7 => 'électrique',
            8 => 'électrique',
            9 => 'électrique',
            10 => 'électrique',
        ];

        foreach ($types as $number => $type) {
            Bed::updateOrCreate(
                ['bed_number' => $number],
                [
                    'bed_type' => $type,
                    'room_number' => (int) ceil($number / 2),
                    'is_available' => true,
                ]
            );
        }
    }
}