<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materiaCategory = Category::where('name', 'Matéria Prima')->first();
        $embalagemCategory = Category::where('name', 'Embalagens')->first();
        $equipamentoCategory = Category::where('name', 'Equipamentos')->first();

        // Produtos de Matéria Prima
        Product::create([
            'name' => 'Aço Inoxidável 304',
            'description' => 'Chapa de aço inoxidável 304',
            'detailed_description' => 'Chapa de aço inoxidável 304 com espessura de 2mm, ideal para aplicações industriais.',
            'sku' => 'ACO-304-001',
            'barcode' => '7891234567890',
            'price' => 150.00,
            'cost_price' => 120.00,
            'stock_quantity' => 50,
            'min_stock' => 10,
            'max_stock' => 200,
            'status' => 'active',
            'weight' => 25.500,
            'dimensions' => '100x50x2cm',
            'brand' => 'Aços Brasil',
            'category_id' => $materiaCategory->id,
        ]);

        Product::create([
            'name' => 'Alumínio 6061',
            'description' => 'Barra de alumínio 6061',
            'detailed_description' => 'Barra de alumínio 6061 T6, alta resistência e usinabilidade.',
            'sku' => 'ALU-6061-001',
            'price' => 85.00,
            'cost_price' => 65.00,
            'stock_quantity' => 5, // Estoque baixo para demonstrar alerta
            'min_stock' => 15,
            'max_stock' => 100,
            'status' => 'active',
            'weight' => 12.300,
            'dimensions' => '200x5x5cm',
            'brand' => 'Alumínios SP',
            'category_id' => $materiaCategory->id,
        ]);

        // Produtos de Embalagem
        Product::create([
            'name' => 'Caixa de Papelão 30x20x15',
            'description' => 'Caixa de papelão ondulado',
            'detailed_description' => 'Caixa de papelão ondulado duplo, resistente para transporte.',
            'sku' => 'CX-PAP-001',
            'price' => 2.50,
            'cost_price' => 1.80,
            'stock_quantity' => 500,
            'min_stock' => 100,
            'max_stock' => 2000,
            'status' => 'active',
            'weight' => 0.150,
            'dimensions' => '30x20x15cm',
            'brand' => 'Embalagens Ltda',
            'category_id' => $embalagemCategory->id,
        ]);

        // Produtos de Equipamento
        Product::create([
            'name' => 'Furadeira Industrial 1200W',
            'description' => 'Furadeira industrial de alta potência',
            'detailed_description' => 'Furadeira industrial com motor de 1200W, ideal para trabalhos pesados.',
            'sku' => 'FUR-IND-001',
            'price' => 850.00,
            'cost_price' => 650.00,
            'stock_quantity' => 3,
            'min_stock' => 2,
            'max_stock' => 10,
            'status' => 'active',
            'is_customizable' => true,
            'weight' => 3.500,
            'dimensions' => '35x25x30cm',
            'brand' => 'Ferramentas Pro',
            'model' => 'FP-1200',
            'featured' => true,
            'category_id' => $equipamentoCategory->id,
        ]);

        Product::create([
            'name' => 'Computador Desktop i7',
            'description' => 'Computador desktop para escritório',
            'detailed_description' => 'Computador desktop com processador Intel i7, 16GB RAM, SSD 512GB.',
            'sku' => 'PC-I7-001',
            'price' => 3500.00,
            'cost_price' => 2800.00,
            'stock_quantity' => 8,
            'min_stock' => 5,
            'max_stock' => 20,
            'status' => 'active',
            'is_customizable' => true,
            'custom_fields' => [
                'processador' => 'Intel i7-12700',
                'memoria' => '16GB DDR4',
                'armazenamento' => 'SSD 512GB',
                'garantia' => '2 anos'
            ],
            'weight' => 8.500,
            'dimensions' => '45x20x40cm',
            'brand' => 'TechPro',
            'model' => 'TP-I7-2024',
            'featured' => true,
            'category_id' => $equipamentoCategory->id,
        ]);
    }
}
