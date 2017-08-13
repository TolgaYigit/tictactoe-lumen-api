<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class GamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('games')->insert([
        [
            'start_time' => Carbon::now(),
            'pX' => 1,
            'pO' => 2,
            'size' => 3,
            'status' => 2
        ],[
            'start_time' => Carbon::now(),
            'pX' => 1,
            'pO' => 2,
            'size' => 3,
            'status' => 2
        ]
        ]);
    }
}
