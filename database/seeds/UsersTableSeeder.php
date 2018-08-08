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
            'name' => 'Mizanur',
            'email' => 'mizanur@gmail.com',
            'password' => bcrypt('mizanur'),
        ]);
        DB::table('users')->insert([
            'name' => 'Mizan',
            'email' => 'mizan@gmail.com',
            'password' => bcrypt('mizan'),
        ]);
        DB::table('users')->insert([
            'name' => 'Mehedi',
            'email' => 'mehedi@gmail.com',
            'password' => bcrypt('mehedi'),
        ]);
    }
}
