<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário admin
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@lumiserp.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Criar usuário vendedor
        $vendedor = User::create([
            'name' => 'João Vendedor',
            'email' => 'vendedor@lumiserp.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Criar usuário comprador
        $comprador = User::create([
            'name' => 'Maria Compradora',
            'email' => 'comprador@lumiserp.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
