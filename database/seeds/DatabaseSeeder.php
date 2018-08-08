<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        /*
        factory(User::class, 3)->create();
        factory(User::class)->create([
            'name' => 'Mizanur',
            'email' => 'mizanur@gmail.com',
        ]);
        factory(User::class)->create([
            'name' => 'Mizan',
            'email' => 'mizan@gmail.com',
            'password' => bcrypt('mizan'),
        ]);*/
    }
}
