<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder  extends Seeder
{
    public function run(): void
    {

        DB::table('users')->updateOrInsert(
            ['email' => 'ranimbejaoui50@gmail.com'], // يمنع duplication
            [
                'name' => 'Ranim',
                'lastname' => 'Bejaoui',
                'username' => 'Ranimbejaoui',
                'role' => 2, // admin
                'password' => Hash::make('123456'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
