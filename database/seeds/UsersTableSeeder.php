<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
    	[
            'username' => 'playerMe',
            'password' => app()->make('hash')->make('secret'),
            'is_admin' => true,
        ],
        [
            'username' => 'playerYou',
            'password' => app()->make('hash')->make('secret'),
            'is_admin' => false,
        ]
        ]);
    }
}
