<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // CREATE ROLE
        // Role::create([
        //     'name' => 'admin',
        //     'guard_name' => 'web'
        // ]);
        // Role::create([
        //     'name' => 'owner',
        //     'guard_name' => 'web'
        // ]);
        // Role::create([
        //     'name' => 'customer',
        //     'guard_name' => 'web'
        // ]);

        User::create([
            'name' => 'Owner',
            'email' => 'Owner@moku.com',
            'username' => 'owner',
            'password' => bcrypt('12345678'),
        ])->assignRole('owner');
        
    }
}
