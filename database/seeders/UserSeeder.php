<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create a generic unrestricted admin user
        User::factory()->count(1)->create([
            'name'  => 'Admin User',
            'email' => 'admin@user.com',
            'age'   => 18,
        ]);

        // create a restricted user
        User::factory()->count(1)->create([
            'name'  => 'Restricted User',
            'email' => 'restricted@user.com',
            'age'   => 17,
        ]);

        // create the rest of the users
        User::factory()->count(18)->create();
    }
}
