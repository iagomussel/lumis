<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class EnhancedCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Categorias para E-commerce (produtos finais)
            [
                'name' => 'Canecas',
                'description' => 'Canecas personalizadas para sublimação',
                'type' => 'ativo',
                'slug' => 'canecas',
                'active' => true,
                'show_in_ecommerce' => true,
                'internal_notes' => 'Produtos finais para venda online'
            ],
            [
                'name' => 'Camisetas',
                'description' => 'Camisetas para sublimação',
                'type' => 'ativo',
                'slug' => 'camisetas',
                'active' => true,
                'show_in_ecommerce' => true,
                'internal_notes' => 'Produtos finais para venda online'
            ],
            [
                'name' => 'Almofadas',
                'description' => 'Almofadas personalizadas',
                'type' => 'ativo',
                'slug' => 'almofadas',
                'active' => true,
                'show_in_ecommerce' => true,
                'internal_notes' => 'Produtos finais para venda online'
            ],
            [
                'name' => 'Quadros',
                'description' => 'Quadros e placas decorativas',
                'type' => 'ativo',
                'slug' => 'quadros',
                'active' => true,
                'show_in_ecommerce' => true,
                'internal_notes' => 'Produtos finais para venda online'
            ],
            [
                'name' => 'Chaveiros',
                'description' => 'Chaveiros personalizados',
                'type' => 'ativo',
                'slug' => 'chaveiros',
                'active' => true,
                'show_in_ecommerce' => true,
                'internal_notes' => 'Produtos finais para venda online'
            ],

            // Categorias Internas - Insumos
            [
                'name' => 'Papel Sublimático',
                'description' => 'Papéis para impressão sublimática',
                'type' => 'insumo',
                'slug' => 'papel-sublimatico',
                'active' => true,
                'show_in_ecommerce' => false,
                'internal_notes' => 'Insumo consumível para produção. Controle de estoque crítico.'
            ],
            [
                'name' => 'Tintas Sublimáticas',
                'description' => 'Tintas e refis para impressoras sublimáticas',
                'type' => 'insumo',
                'slug' => 'tintas-sublimaticas',
                'active' => true,
                'show_in_ecommerce' => false,
                'internal_notes' => 'Insumo de alto custo. Monitorar rendimento por impressão.'
            ],
            [
                'name' => 'Blanks - Canecas',
                'description' => 'Canecas em branco para sublimação',
                'type' => 'insumo',
                'slug' => 'blanks-canecas',
                'active' => true,
                'show_in_ecommerce' => false,
                'internal_notes' => 'Matéria-prima. Calcular custo por unidade + quebras.'
            ],
            [
                'name' => 'Blanks - Tecidos',
                'description' => 'Camisetas e tecidos em branco',
                'type' => 'insumo',
                'slug' => 'blanks-tecidos',
                'active' => true,
                'show_in_ecommerce' => false,
                'internal_notes' => 'Matéria-prima têxtil. Controlar tamanhos e cores.'
            ],
            [
                'name' => 'Materiais Diversos',
                'description' => 'Materiais diversos para sublimação',
                'type' => 'insumo',
                'slug' => 'materiais-diversos',
                'active' => true,
                'show_in_ecommerce' => false,
                'internal_notes' => 'MDF, acrílico, mouse pads, etc.'
            ],

            // Categorias Internas - Equipamentos/Ativos
            [
                'name' => 'Impressoras',
                'description' => 'Impressoras sublimáticas e equipamentos',
                'type' => 'ativo',
                'slug' => 'impressoras',
                'active' => true,
                'show_in_ecommerce' => false,
                'internal_notes' => 'Equipamentos de produção. Depreciar em 5 anos.'
            ],
            [
                'name' => 'Prensas Térmicas',
                'description' => 'Prensas e equipamentos de calor',
                'type' => 'ativo',
                'slug' => 'prensas-termicas',
                'active' => true,
                'show_in_ecommerce' => false,
                'internal_notes' => 'Equipamentos principais. Manutenção preventiva obrigatória.'
            ],
            [
                'name' => 'Ferramentas e Acessórios',
                'description' => 'Ferramentas auxiliares e acessórios',
                'type' => 'ativo',
                'slug' => 'ferramentas-acessorios',
                'active' => true,
                'show_in_ecommerce' => false,
                'internal_notes' => 'Espátulas, papel protetor, réguas, etc.'
            ],
            [
                'name' => 'Móveis e Instalações',
                'description' => 'Móveis, bancadas e infraestrutura',
                'type' => 'ativo',
                'slug' => 'moveis-instalacoes',
                'active' => true,
                'show_in_ecommerce' => false,
                'internal_notes' => 'Infraestrutura física. Depreciação lenta.'
            ],

            // Categorias de Controle
            [
                'name' => 'Embalagens',
                'description' => 'Caixas, sacolas e materiais de embalagem',
                'type' => 'insumo',
                'slug' => 'embalagens',
                'active' => true,
                'show_in_ecommerce' => false,
                'internal_notes' => 'Custo adicional por produto. Impacta margem final.'
            ],
            [
                'name' => 'Marketing e Comunicação',
                'description' => 'Materiais de marketing e comunicação',
                'type' => 'insumo',
                'slug' => 'marketing-comunicacao',
                'active' => true,
                'show_in_ecommerce' => false,
                'internal_notes' => 'Cartões, flyers, adesivos promocionais.'
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
            
            $this->command->info("Created/Updated category: {$categoryData['name']}");
        }

        $this->command->info('Enhanced categories seeded successfully!');
        $this->command->info('E-commerce categories: ' . Category::ecommerce()->count());
        $this->command->info('Internal categories: ' . Category::internal()->count());
        $this->command->info('Insumos: ' . Category::insumos()->count());
        $this->command->info('Ativos: ' . Category::ativos()->count());
    }
} 