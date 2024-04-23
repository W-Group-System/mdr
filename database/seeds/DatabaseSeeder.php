<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        DB::table('users')->insert([
            'lastname' => 'admin',
            'firstname' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'account_role' => 0,
            'account_status' => 'A',
        ]);
    }
}
