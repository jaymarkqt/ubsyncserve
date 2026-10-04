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
        User::firstOrCreate(
            ['email' => 'manager@ubsyncserve.com'],
            [
                'name' => 'Manager',
                'password' => bcrypt('manager123'),
                'role' => 'manager',
            ],
        );

        User::firstOrCreate(
            ['email' => 'waiter@ubsyncserve.com'],
            [
                'name' => 'Waiter',
                'password' => bcrypt('waiter123'),
                'role' => 'waiter',
            ],
        );

        $this->call(ProductSeeder::class);
    }
}
