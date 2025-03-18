<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompletionStatus;

class CompletionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['status' => 'Pending'],
            ['status' => 'In Progress'],
            ['status' => 'Completed'],
            ['status' => 'Incomplete'],
            ['status' => 'Cancelled'],
            ['status' => 'On Hold'],
            ['status' => 'Awaiting Approval'],
            ['status' => 'Rejected'],
        ];

        foreach ($statuses as $status) {
            CompletionStatus::create($status);
        }
    }
}
