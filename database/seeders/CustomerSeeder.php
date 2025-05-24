<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cliente PF
        Customer::create([
            'name' => 'João Silva Santos',
            'email' => 'joao.silva@email.com',
            'password' => Hash::make('password'),
            'phone' => '(11) 3456-7890',
            'mobile' => '(11) 98765-4321',
            'document' => '123.456.789-01',
            'document_type' => 'cpf',
            'type' => 'individual',
            'address' => 'Rua das Flores',
            'address_number' => '123',
            'complement' => 'Apto 45',
            'neighborhood' => 'Vila Madalena',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '05432-123',
            'country' => 'Brasil',
            'status' => 'active',
            'credit_limit' => 5000.00,
            'notes' => 'Cliente fiel, sempre pontual nos pagamentos.',
            'birth_date' => '1985-03-15',
            'gender' => 'M',
        ]);

        // Cliente PJ
        Customer::create([
            'name' => 'TechSoft Ltda',
            'company_name' => 'TechSoft Soluções em Software Ltda',
            'email' => 'contato@techsoft.com.br',
            'password' => Hash::make('password'),
            'phone' => '(11) 4567-8901',
            'mobile' => '(11) 99876-5432',
            'document' => '12.345.678/0001-90',
            'document_type' => 'cnpj',
            'type' => 'company',
            'state_registration' => '123.456.789.123',
            'address' => 'Av. Paulista',
            'address_number' => '1000',
            'complement' => 'Conj. 801',
            'neighborhood' => 'Bela Vista',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
            'country' => 'Brasil',
            'status' => 'active',
            'credit_limit' => 50000.00,
            'notes' => 'Empresa de tecnologia, parceira de longa data.',
        ]);

        // Cliente Bloqueado
        Customer::create([
            'name' => 'Maria Oliveira',
            'email' => 'maria.oliveira@email.com',
            'password' => Hash::make('password'),
            'phone' => '(21) 2345-6789',
            'mobile' => '(21) 98765-1234',
            'document' => '987.654.321-09',
            'document_type' => 'cpf',
            'type' => 'individual',
            'address' => 'Rua da Praia',
            'address_number' => '456',
            'neighborhood' => 'Copacabana',
            'city' => 'Rio de Janeiro',
            'state' => 'RJ',
            'zip_code' => '22041-010',
            'country' => 'Brasil',
            'status' => 'blocked',
            'credit_limit' => 2000.00,
            'notes' => 'Cliente com histórico de inadimplência. Bloqueado temporariamente.',
            'birth_date' => '1992-07-20',
            'gender' => 'F',
        ]);

        // Cliente Inativo
        Customer::create([
            'name' => 'Pedro Costa',
            'email' => 'pedro.costa@email.com',
            'password' => Hash::make('password'),
            'phone' => '(31) 3456-7890',
            'document' => '456.789.123-45',
            'document_type' => 'cpf',
            'type' => 'individual',
            'address' => 'Rua dos Inconfidentes',
            'address_number' => '789',
            'neighborhood' => 'Centro',
            'city' => 'Belo Horizonte',
            'state' => 'MG',
            'zip_code' => '30112-000',
            'country' => 'Brasil',
            'status' => 'inactive',
            'credit_limit' => 1000.00,
            'notes' => 'Cliente que não faz compras há mais de 1 ano.',
            'birth_date' => '1978-12-05',
            'gender' => 'M',
        ]);

        // Mais clientes para testar paginação
        Customer::create([
            'name' => 'Ana Paula Ferreira',
            'email' => 'ana.ferreira@email.com',
            'password' => Hash::make('password'),
            'mobile' => '(85) 99123-4567',
            'document' => '789.123.456-78',
            'document_type' => 'cpf',
            'type' => 'individual',
            'city' => 'Fortaleza',
            'state' => 'CE',
            'status' => 'active',
            'credit_limit' => 3000.00,
            'birth_date' => '1990-09-18',
            'gender' => 'F',
        ]);

        Customer::create([
            'name' => 'Carlos Eduardo Lopes',
            'email' => 'carlos.lopes@email.com',
            'password' => Hash::make('password'),
            'phone' => '(47) 3234-5678',
            'document' => '321.654.987-01',
            'document_type' => 'cpf',
            'type' => 'individual',
            'address' => 'Rua das Palmeiras',
            'address_number' => '321',
            'city' => 'Blumenau',
            'state' => 'SC',
            'zip_code' => '89010-000',
            'status' => 'active',
            'credit_limit' => 4000.00,
            'birth_date' => '1983-04-12',
            'gender' => 'M',
        ]);
    }
} 