<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'wyl',
            'email' => 'wyl@gmail.com',
            'password' => 'helloworld',
            'phone_no' => '0394239048',
            'address' => 'Yangon'
        ]);
    }
}
