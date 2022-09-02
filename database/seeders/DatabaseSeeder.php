<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'username' => 'webstal',
            'email' => 'admin@webstal.com',
            'password' => Hash::make('m1cr0')
        ]);
    }
}
