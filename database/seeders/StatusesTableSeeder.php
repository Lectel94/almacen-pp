<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $statuses = [
            ['description' => 'Pending', 'color' => 'yellow'],
            ['description' => 'In Process', 'color' => 'blue'],
            ['description' => 'Completed', 'color' => 'green'],
            ['description' => 'Cancelled', 'color' => 'red'],
        ];

        DB::table('statuses')->insert($statuses);
    }
}
