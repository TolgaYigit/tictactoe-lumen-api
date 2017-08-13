<?php

use Illuminate\Database\Seeder;

class MovesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('moves')->insert([
    	[
    	    'game_id' => 1,
            'user_id' => 1,
            'x_axis' => 0,
            'y_axis' => 0,
            'turn' => 1
        ],
        [
    	    'game_id' => 1,
            'user_id' => 2,
            'x_axis' => 1,
            'y_axis' => 0,
            'turn' => 2
        ],
        [
    	    'game_id' => 1,
            'user_id' => 1,
            'x_axis' => 1,
            'y_axis' => 1,
            'turn' => 3
        ],
        [
            'game_id' => 1,
            'user_id' => 2,
            'x_axis' => 0,
            'y_axis' => 1,
            'turn' => 4
        ],[
            'game_id' => 2,
            'user_id' => 1,
            'x_axis' => 1,
            'y_axis' => 1,
            'turn' => 4
        ],[
            'game_id' => 2,
            'user_id' => 1,
            'x_axis' => 1,
            'y_axis' => 2,
            'turn' => 4
        ],[
    	    'game_id' => 2,
            'user_id' => 1,
            'x_axis' => 1,
            'y_axis' => 3,
            'turn' => 4
        ]
        ]);
    }
}
