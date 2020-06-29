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
            'email' => 'hola@programacionymas.com',
            'name' => 'Juan Ramos',
            'email_verified_at' => now(),
            'password' => bcrypt('123123123'),
            'working_hours' => 3
        ]);
    }
}
