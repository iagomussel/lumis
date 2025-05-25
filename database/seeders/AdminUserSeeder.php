<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário admin padrão se não existir
        User::firstOrCreate(
            ['email' => 'admin@lumiserp.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@lumiserp.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Criar usuário de teste se não existir
        User::firstOrCreate(
            ['email' => 'test@test.com'],
            [
                'name' => 'Usuário Teste',
                'email' => 'test@test.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        echo "✅ Usuários criados:\n";
        echo "- admin@lumiserp.com (senha: admin123)\n";
        echo "- test@test.com (senha: password)\n";
    }
}
