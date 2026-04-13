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
        User::updateOrCreate(
            ['email' => 'naichaakeyaa@gmail.com'],
            [
                'name' => 'Naicha Keya',
                'username' => 'naicha',
                'role' => 'admin',
                'password' => \Illuminate\Support\Facades\Hash::make('nanaii8872'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'azahrakeylaa45@gmail.com'],
            [
                'name' => 'Azzahra Keyla',
                'username' => '3chayy',
                'role' => 'admin',
                'password' => \Illuminate\Support\Facades\Hash::make('nainay2278'),
            ]
        );

        $this->call(BookSeeder::class);
    }
}
