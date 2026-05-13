<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Manager',
            'email' => 'manager@ubsyncserve.com',
            'password' => bcrypt('manager123'),
            'role' => 'manager',
        ]);

        User::create([
            'name' => 'Waiter',
            'email' => 'waiter@ubsyncserve.com',
            'password' => bcrypt('waiter123'),
            'role' => 'waiter',
        ]);
    }
}
