<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Recurrency;

class RecurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $frequencies = [
            ['frequency' => 'hourly'],
            ['frequency'=> 'daily'],
            ['frequency' => 'monthly'],
            ['frequency' => 'annually'],
        ];

        foreach($frequencies as $frequency){
            Recurrency::create($frequency);

        };
    }
}
