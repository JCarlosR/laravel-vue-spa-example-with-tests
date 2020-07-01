<?php

use App\Models\User;
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
        User::create([
            'email' => 'admin@programacionymas.com',
            'name' => 'Juan Ramos',
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => bcrypt('123123123'),
            'working_hours' => 3
        ]);

        User::create([
            'email' => 'manager@programacionymas.com',
            'name' => 'User Manager',
            'role' => 'manager',
            'email_verified_at' => now(),
            'password' => bcrypt('123123123'),
            'working_hours' => 3
        ]);

        User::create([
            'email' => 'user@programacionymas.com',
            'name' => 'Regular User',
            'role' => 'user',
            'email_verified_at' => now(),
            'password' => bcrypt('123123123'),
            'working_hours' => 3
        ]);
    }
}
