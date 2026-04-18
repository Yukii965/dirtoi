<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Ton compte sera toujours là !
        User::create([
            'name' => 'Njara',
            'email' => 'njara.aintsoa@gmail.com',
            'password' => Hash::make('123456789'), // Change le mot de passe ici
        ]);

        $this->call(ProductSeeder::class);
    }
}