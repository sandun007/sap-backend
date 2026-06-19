<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
       public function run(): void
    {
        \App\Models\User::create([
            'name' => 'SAP',
            'email' => 'admin@sap.com',
            'password' => bcrypt('password'), 
            'role' => 'admin', 
        ]);
    }


}
