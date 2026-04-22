<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Menu;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin Ibu Ida',
            'email' => 'admin@ibuida.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Regular user
        User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'points' => 0,
        ]);

        // Sample Menus
        Menu::create([
            'name' => 'Sayur Asem',
            'description' => 'Sayur asem segar dengan jagung manis',
            'price' => 10000,
            'points_reward' => 10,
            'category' => 'Makanan'
        ]);

        Menu::create([
            'name' => 'Air Putih',
            'description' => 'Air mineral dingin',
            'price' => 1000,
            'points_reward' => 1,
            'category' => 'Minuman'
        ]);

        Menu::create([
            'name' => 'Nasi Rames Spesial',
            'description' => 'Nasi dengan lauk ayam goreng, telur, tempe, dan sambal',
            'price' => 25000,
            'points_reward' => 25,
            'category' => 'Makanan'
        ]);
        
        Menu::create([
            'name' => 'Es Teh Manis',
            'description' => 'Es teh manis segar',
            'price' => 5000,
            'points_reward' => 5,
            'category' => 'Minuman'
        ]);
    }
}
