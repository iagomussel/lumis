<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class InternalProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get internal categories
        $categories = [
            'papel_sublimatico' => Category::where('slug', 'papel-sublimatico')->first(),
            'tintas' => Category::where('slug', 'tintas-sublimaticas')->first(),
            'blanks_canecas' => Category::where('slug', 'blanks-canecas')->first(),
            'blanks_tecidos' => Category::where('slug', 'blanks-tecidos')->first(),
            'materiais_diversos' => Category::where('slug', 'materiais-diversos')->first(),
            'impressoras' => Category::where('slug', 'impressoras')->first(),
            'prensas' => Category::where('slug', 'prensas-termicas')->first(),
            'ferramentas' => Category::where('slug', 'ferramentas-acessorios')->first(),
            'moveis' => Category::where('slug', 'moveis-instalacoes')->first(),
            'embalagens' => Category::where('slug', 'embalagens')->first(),
            'marketing' => Category::where('slug', 'marketing-comunicacao')->first(),
        ];

        $internalProducts = [
            // Papel Sublimático
            [
                'name' => 'Papel Sublimático A4 90g - Pacote 100 folhas',
                'description' => 'Papel especial para sublimação em folha A4',
                'sku' => 'PAP-SUB-A4-100',
                'price' => 45.00, // Preço de referência para cálculo
                'cost_price' => 35.80,
                'stock_quantity' => 25,
                'min_stock' => 10,
                'max_stock' => 100,
                'status' => 'active',
                'online_sale' => false,
                'weight' => 0.600,
                'category_id' => $categories['papel_sublimatico']?->id,
                'specifications' => [
                    'formato' => 'A4',
                    'gramatura' => '90g',
                    'quantidade' => '100 folhas'
                ]
            ],
            [
                'name' => 'Papel Sublimático A3 120g - Pacote 50 folhas',
                'description' => 'Papel especial para sublimação em folha A3',
                'sku' => 'PAP-SUB-A3-50',
                'price' => 55.00,
                'cost_price' => 42.30,
                'stock_quantity' => 15,
                'min_stock' => 5,
                'max_stock' => 50,
                'status' => 'active',
                'online_sale' => false,
                'weight' => 0.800,
                'category_id' => $categories['papel_sublimatico']?->id,
                'specifications' => [
                    'formato' => 'A3',
                    'gramatura' => '120g',
                    'quantidade' => '50 folhas'
                ]
            ],

            // Tintas Sublimáticas
            [
                'name' => 'Tinta Sublimática Ciano 100ml - Epson',
                'description' => 'Tinta sublimática ciano compatível Epson',
                'sku' => 'TINT-CY-100-EP',
                'price' => 28.90,
                'cost_price' => 18.50,
                'stock_quantity' => 12,
                'min_stock' => 8,
                'max_stock' => 30,
                'status' => 'active',
                'online_sale' => false,
                'weight' => 0.120,
                'category_id' => $categories['tintas']?->id,
                'specifications' => [
                    'cor' => 'Ciano',
                    'volume' => '100ml',
                    'compatibilidade' => 'Epson'
                ]
            ],
            [
                'name' => 'Kit 4 Tintas Sublimáticas 100ml - CMYK',
                'description' => 'Kit completo com 4 cores para sublimação',
                'sku' => 'KIT-TINT-CMYK-100',
                'price' => 98.00,
                'cost_price' => 72.40,
                'stock_quantity' => 8,
                'min_stock' => 5,
                'max_stock' => 20,
                'status' => 'active',
                'online_sale' => false,
                'weight' => 0.480,
                'category_id' => $categories['tintas']?->id,
                'specifications' => [
                    'cores' => 'CMYK',
                    'volume_total' => '400ml',
                    'rendimento' => '~200 impressões A4'
                ]
            ],

            // Blanks - Canecas
            [
                'name' => 'Caneca Branca Lisa 325ml - Caixa 36 unidades',
                'description' => 'Canecas em branco para sublimação',
                'sku' => 'BLANK-CAN-325-36',
                'price' => 216.00, // R$ 6,00 por unidade
                'cost_price' => 162.00, // R$ 4,50 por unidade
                'stock_quantity' => 5, // 5 caixas = 180 canecas
                'min_stock' => 2,
                'max_stock' => 15,
                'status' => 'active',
                'online_sale' => false,
                'weight' => 12.600, // 36 canecas
                'category_id' => $categories['blanks_canecas']?->id,
                'specifications' => [
                    'material' => 'Porcelana',
                    'capacidade' => '325ml',
                    'quantidade_caixa' => '36 unidades',
                    'custo_unitario' => 'R$ 4,50'
                ]
            ],

            // Blanks - Tecidos
            [
                'name' => 'Camiseta 100% Poliéster Branca M - Pacote 12 un',
                'description' => 'Camisetas em branco tamanho M',
                'sku' => 'BLANK-CAM-POL-M-12',
                'price' => 168.00, // R$ 14,00 por unidade
                'cost_price' => 108.00, // R$ 9,00 por unidade
                'stock_quantity' => 8, // 8 pacotes = 96 camisetas
                'min_stock' => 3,
                'max_stock' => 20,
                'status' => 'active',
                'online_sale' => false,
                'weight' => 2.160, // 12 camisetas
                'category_id' => $categories['blanks_tecidos']?->id,
                'specifications' => [
                    'material' => '100% Poliéster',
                    'tamanho' => 'M',
                    'quantidade_pacote' => '12 unidades',
                    'custo_unitario' => 'R$ 9,00'
                ]
            ],

            // Equipamentos - Impressoras
            [
                'name' => 'Impressora Epson L3150 EcoTank',
                'description' => 'Impressora multifuncional para sublimação',
                'sku' => 'EQUIP-IMP-L3150',
                'price' => 1200.00, // Valor patrimonial
                'cost_price' => 950.00,
                'stock_quantity' => 1,
                'min_stock' => 1,
                'max_stock' => 3,
                'status' => 'active',
                'online_sale' => false,
                'weight' => 8.500,
                'category_id' => $categories['impressoras']?->id,
                'specifications' => [
                    'modelo' => 'L3150',
                    'marca' => 'Epson',
                    'tipo' => 'EcoTank',
                    'vida_util' => '5 anos',
                    'depreciacao_anual' => '20%'
                ]
            ],

            // Equipamentos - Prensas
            [
                'name' => 'Prensa Térmica 38x38cm Digital',
                'description' => 'Prensa térmica plana com controle digital',
                'sku' => 'EQUIP-PRE-3838-DIG',
                'price' => 850.00,
                'cost_price' => 680.00,
                'stock_quantity' => 2,
                'min_stock' => 1,
                'max_stock' => 4,
                'status' => 'active',
                'online_sale' => false,
                'weight' => 25.000,
                'category_id' => $categories['prensas']?->id,
                'specifications' => [
                    'dimensoes' => '38x38cm',
                    'tipo' => 'Plana',
                    'controle' => 'Digital',
                    'temperatura_max' => '230°C',
                    'vida_util' => '8 anos'
                ]
            ],

            // Ferramentas
            [
                'name' => 'Kit Ferramentas Sublimação - 15 peças',
                'description' => 'Kit completo de ferramentas para sublimação',
                'sku' => 'FERR-KIT-SUB-15',
                'price' => 89.90,
                'cost_price' => 65.50,
                'stock_quantity' => 3,
                'min_stock' => 2,
                'max_stock' => 10,
                'status' => 'active',
                'online_sale' => false,
                'weight' => 1.200,
                'category_id' => $categories['ferramentas']?->id,
                'specifications' => [
                    'quantidade' => '15 peças',
                    'inclui' => 'Espátulas, réguas, papel protetor',
                    'material' => 'Silicone e metal'
                ]
            ],

            // Embalagens
            [
                'name' => 'Caixas de Papelão 15x15x10cm - Pacote 50 un',
                'description' => 'Caixas para embalagem de canecas',
                'sku' => 'EMB-CX-1515-50',
                'price' => 75.00, // R$ 1,50 por caixa
                'cost_price' => 52.50, // R$ 1,05 por caixa
                'stock_quantity' => 12, // 12 pacotes = 600 caixas
                'min_stock' => 5,
                'max_stock' => 30,
                'status' => 'active',
                'online_sale' => false,
                'weight' => 2.500,
                'category_id' => $categories['embalagens']?->id,
                'specifications' => [
                    'dimensoes' => '15x15x10cm',
                    'material' => 'Papelão ondulado',
                    'quantidade_pacote' => '50 unidades',
                    'uso' => 'Canecas e produtos pequenos'
                ]
            ],
        ];

        foreach ($internalProducts as $productData) {
            if ($productData['category_id']) {
                Product::create($productData);
                $this->command->info("Created internal product: {$productData['name']}");
            } else {
                $this->command->warn("Skipped product (category not found): {$productData['name']}");
            }
        }

        $this->command->info('Internal products seeded successfully!');
        
        // Statistics
        $totalInternalProducts = Product::whereHas('category', function($query) {
            $query->internal();
        })->count();
        
        $totalInternalValue = Product::whereHas('category', function($query) {
            $query->internal();
        })->sum(\DB::raw('price * stock_quantity'));
        
        $totalInternalCost = Product::whereHas('category', function($query) {
            $query->internal();
        })->sum(\DB::raw('cost_price * stock_quantity'));

        $this->command->info("Total internal products: {$totalInternalProducts}");
        $this->command->info("Total internal inventory value: R$ " . number_format($totalInternalValue, 2, ',', '.'));
        $this->command->info("Total internal inventory cost: R$ " . number_format($totalInternalCost, 2, ',', '.'));
        
        if ($totalInternalCost > 0) {
            $overallROI = round((($totalInternalValue - $totalInternalCost) / $totalInternalCost) * 100, 2);
            $this->command->info("Overall internal inventory ROI: {$overallROI}%");
        }
    }
} 