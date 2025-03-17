<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriorityStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priority_statuses = [
            ['name' => 'Low'],
            ['name' => 'Medium'],
            ['name' => 'High'],
            ['name' => 'Urgent'],
        ];

        foreach ($priority_statuses as $status) {
            DB::table('priority_statuses')->insert([
                'priority_status' => $status['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
