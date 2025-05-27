<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use Faker\Factory as Faker;

class SublimationProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_BR');
        
        // Categorias específicas para sublimação
        $categories = $this->createCategories();
        
        // Produtos base para sublimação
        $productTemplates = $this->getProductTemplates();
        
        $productId = 1;
        
        foreach ($productTemplates as $template) {
            // Criar múltiplas variações do mesmo produto
            $variations = $template['variations'] ?? 1;
            
            for ($i = 0; $i < $variations; $i++) {
                $product = $this->createProduct($template, $categories, $faker, $productId);
                
                // Criar variações para produtos que suportam
                if (isset($template['variant_options'])) {
                    $this->createProductVariants($product, $template['variant_options'], $faker);
                }
                
                $productId++;
            }
        }
    }
    
    private function createCategories()
    {
        return [
            'canecas' => Category::firstOrCreate(['name' => 'Canecas e Copos'], [
                'description' => 'Canecas, copos e recipientes para sublimação',
                'type' => 'ativo',
                'active' => true,
                'slug' => 'canecas-copos'
            ]),
            'camisetas' => Category::firstOrCreate(['name' => 'Camisetas e Vestuário'], [
                'description' => 'Camisetas, camisas e roupas para sublimação',
                'type' => 'ativo',
                'active' => true,
                'slug' => 'camisetas-vestuario'
            ]),
            'almofadas' => Category::firstOrCreate(['name' => 'Almofadas e Têxtil'], [
                'description' => 'Almofadas, tapetes e produtos têxteis',
                'type' => 'ativo',
                'active' => true,
                'slug' => 'almofadas-textil'
            ]),
            'quadros' => Category::firstOrCreate(['name' => 'Quadros e Decoração'], [
                'description' => 'Quadros, placas e itens decorativos',
                'type' => 'ativo',
                'active' => true,
                'slug' => 'quadros-decoracao'
            ]),
            'chaveiros' => Category::firstOrCreate(['name' => 'Chaveiros e Brindes'], [
                'description' => 'Chaveiros, brindes e produtos promocionais',
                'type' => 'ativo',
                'active' => true,
                'slug' => 'chaveiros-brindes'
            ]),
            'azulejos' => Category::firstOrCreate(['name' => 'Azulejos e Cerâmica'], [
                'description' => 'Azulejos decorativos e produtos cerâmicos',
                'type' => 'ativo',
                'active' => true,
                'slug' => 'azulejos-ceramica'
            ]),
            'mouse_pads' => Category::firstOrCreate(['name' => 'Mouse Pads e Acessórios'], [
                'description' => 'Mouse pads e acessórios para computador',
                'type' => 'ativo',
                'active' => true,
                'slug' => 'mouse-pads-acessorios'
            ]),
            'papel' => Category::firstOrCreate(['name' => 'Papel e Materiais'], [
                'description' => 'Papéis especiais e materiais para sublimação',
                'type' => 'ativo',
                'active' => true,
                'slug' => 'papel-materiais'
            ])
        ];
    }
    
    private function getProductTemplates()
    {
        return [
            // CANECAS E COPOS (20 produtos)
            [
                'name' => 'Caneca Cerâmica Branca',
                'category' => 'canecas',
                'base_price' => 24.90,
                'cost_price' => 12.50,
                'variations' => 3,
                'variant_options' => [
                    'capacidade' => ['300ml', '325ml', '350ml'],
                    'cor' => ['Branca', 'Bege Claro']
                ]
            ],
            [
                'name' => 'Caneca Mágica Termossensível',
                'category' => 'canecas',
                'base_price' => 39.90,
                'cost_price' => 18.75,
                'variations' => 2,
                'variant_options' => [
                    'capacidade' => ['320ml', '350ml'],
                    'cor' => ['Preta', 'Azul Escura']
                ]
            ],
            [
                'name' => 'Caneca de Chopp Vidro',
                'category' => 'canecas',
                'base_price' => 32.90,
                'cost_price' => 15.50,
                'variations' => 2,
                'variant_options' => [
                    'capacidade' => ['400ml', '500ml']
                ]
            ],
            [
                'name' => 'Copo Long Drink',
                'category' => 'canecas',
                'base_price' => 28.90,
                'cost_price' => 14.25,
                'variations' => 3,
                'variant_options' => [
                    'capacidade' => ['300ml', '350ml', '400ml']
                ]
            ],
            [
                'name' => 'Caneca de Porcelana Premium',
                'category' => 'canecas',
                'base_price' => 45.90,
                'cost_price' => 22.50,
                'variations' => 2,
                'variant_options' => [
                    'formato' => ['Clássico', 'Cônico'],
                    'acabamento' => ['Fosco', 'Brilhante']
                ]
            ],
            [
                'name' => 'Caneca de Alumínio',
                'category' => 'canecas',
                'base_price' => 35.90,
                'cost_price' => 17.25,
                'variations' => 2,
                'variant_options' => [
                    'cor' => ['Branca', 'Prata'],
                    'acabamento' => ['Fosco', 'Brilhante']
                ]
            ],
            [
                'name' => 'Caneca Plástica Infantil',
                'category' => 'canecas',
                'base_price' => 19.90,
                'cost_price' => 9.50,
                'variations' => 4,
                'variant_options' => [
                    'cor' => ['Branca', 'Rosa', 'Azul', 'Verde']
                ]
            ],
            
            // CAMISETAS E VESTUÁRIO (25 produtos)
            [
                'name' => 'Camiseta Poliéster 100%',
                'category' => 'camisetas',
                'base_price' => 29.90,
                'cost_price' => 12.50,
                'variations' => 5,
                'variant_options' => [
                    'tamanho' => ['PP', 'P', 'M', 'G', 'GG', 'XG'],
                    'cor' => ['Branca', 'Preta', 'Cinza', 'Azul Marinho']
                ]
            ],
            [
                'name' => 'Camiseta Baby Look Feminina',
                'category' => 'camisetas',
                'base_price' => 32.90,
                'cost_price' => 14.25,
                'variations' => 4,
                'variant_options' => [
                    'tamanho' => ['PP', 'P', 'M', 'G', 'GG'],
                    'cor' => ['Branca', 'Rosa', 'Lilás', 'Preta']
                ]
            ],
            [
                'name' => 'Regata Masculina Dri-Fit',
                'category' => 'camisetas',
                'base_price' => 34.90,
                'cost_price' => 15.75,
                'variations' => 3,
                'variant_options' => [
                    'tamanho' => ['P', 'M', 'G', 'GG', 'XG'],
                    'cor' => ['Branca', 'Preta', 'Azul']
                ]
            ],
            [
                'name' => 'Camiseta Infantil',
                'category' => 'camisetas',
                'base_price' => 24.90,
                'cost_price' => 10.50,
                'variations' => 4,
                'variant_options' => [
                    'tamanho' => ['2', '4', '6', '8', '10', '12'],
                    'cor' => ['Branca', 'Rosa', 'Azul', 'Amarela']
                ]
            ],
            [
                'name' => 'Polo Masculina Premium',
                'category' => 'camisetas',
                'base_price' => 49.90,
                'cost_price' => 22.50,
                'variations' => 3,
                'variant_options' => [
                    'tamanho' => ['P', 'M', 'G', 'GG', 'XG'],
                    'cor' => ['Branca', 'Preta', 'Azul Marinho']
                ]
            ],
            
            // ALMOFADAS E TÊXTIL (15 produtos)
            [
                'name' => 'Almofada Quadrada',
                'category' => 'almofadas',
                'base_price' => 34.90,
                'cost_price' => 16.25,
                'variations' => 3,
                'variant_options' => [
                    'tamanho' => ['30x30cm', '35x35cm', '40x40cm'],
                    'tecido' => ['Oxford', 'Sarja', 'Linho']
                ]
            ],
            [
                'name' => 'Almofada Retangular',
                'category' => 'almofadas',
                'base_price' => 39.90,
                'cost_price' => 18.50,
                'variations' => 2,
                'variant_options' => [
                    'tamanho' => ['30x50cm', '35x60cm'],
                    'tecido' => ['Oxford', 'Sarja']
                ]
            ],
            [
                'name' => 'Fronha Avulsa',
                'category' => 'almofadas',
                'base_price' => 22.90,
                'cost_price' => 9.75,
                'variations' => 2,
                'variant_options' => [
                    'tamanho' => ['50x70cm', '50x90cm']
                ]
            ],
            [
                'name' => 'Tapete Personalizado',
                'category' => 'almofadas',
                'base_price' => 59.90,
                'cost_price' => 28.50,
                'variations' => 3,
                'variant_options' => [
                    'tamanho' => ['40x60cm', '50x80cm', '60x100cm']
                ]
            ],
            [
                'name' => 'Toalha de Mesa',
                'category' => 'almofadas',
                'base_price' => 89.90,
                'cost_price' => 42.50,
                'variations' => 2,
                'variant_options' => [
                    'tamanho' => ['1,40x1,40m', '1,60x2,20m'],
                    'tecido' => ['Oxford', 'Lona']
                ]
            ],
            
            // QUADROS E DECORAÇÃO (15 produtos)
            [
                'name' => 'Quadro MDF',
                'category' => 'quadros',
                'base_price' => 49.90,
                'cost_price' => 22.50,
                'variations' => 4,
                'variant_options' => [
                    'tamanho' => ['20x30cm', '30x40cm', '40x60cm', '50x70cm'],
                    'moldura' => ['Sem moldura', 'Moldura preta', 'Moldura branca']
                ]
            ],
            [
                'name' => 'Placa Decorativa Metal',
                'category' => 'quadros',
                'base_price' => 39.90,
                'cost_price' => 18.25,
                'variations' => 3,
                'variant_options' => [
                    'tamanho' => ['20x30cm', '25x35cm', '30x40cm'],
                    'acabamento' => ['Fosco', 'Brilhante']
                ]
            ],
            [
                'name' => 'Quadro Canvas',
                'category' => 'quadros',
                'base_price' => 69.90,
                'cost_price' => 32.50,
                'variations' => 3,
                'variant_options' => [
                    'tamanho' => ['30x40cm', '40x60cm', '50x70cm']
                ]
            ],
            [
                'name' => 'Porta Retrato',
                'category' => 'quadros',
                'base_price' => 29.90,
                'cost_price' => 13.75,
                'variations' => 4,
                'variant_options' => [
                    'tamanho' => ['10x15cm', '13x18cm', '15x21cm', '20x30cm'],
                    'cor' => ['Preto', 'Branco', 'Madeira']
                ]
            ],
            
            // CHAVEIROS E BRINDES (10 produtos)
            [
                'name' => 'Chaveiro Acrílico',
                'category' => 'chaveiros',
                'base_price' => 8.90,
                'cost_price' => 3.25,
                'variations' => 5,
                'variant_options' => [
                    'formato' => ['Retangular', 'Redondo', 'Coração', 'Estrela', 'Personalizado'],
                    'tamanho' => ['4x6cm', '5x7cm', '6x8cm']
                ]
            ],
            [
                'name' => 'Chaveiro Metal',
                'category' => 'chaveiros',
                'base_price' => 12.90,
                'cost_price' => 5.50,
                'variations' => 3,
                'variant_options' => [
                    'formato' => ['Retangular', 'Redondo', 'Oval'],
                    'acabamento' => ['Cromado', 'Dourado', 'Prateado']
                ]
            ],
            [
                'name' => 'Ímã de Geladeira',
                'category' => 'chaveiros',
                'base_price' => 6.90,
                'cost_price' => 2.75,
                'variations' => 4,
                'variant_options' => [
                    'formato' => ['Retangular', 'Redondo', 'Quadrado', 'Personalizado'],
                    'tamanho' => ['5x7cm', '6x8cm', '7x10cm']
                ]
            ],
            
            // AZULEJOS E CERÂMICA (8 produtos)
            [
                'name' => 'Azulejo Decorativo',
                'category' => 'azulejos',
                'base_price' => 19.90,
                'cost_price' => 8.50,
                'variations' => 2,
                'variant_options' => [
                    'tamanho' => ['15x15cm', '20x20cm'],
                    'acabamento' => ['Fosco', 'Brilhante']
                ]
            ],
            [
                'name' => 'Prato Decorativo',
                'category' => 'azulejos',
                'base_price' => 35.90,
                'cost_price' => 16.25,
                'variations' => 2,
                'variant_options' => [
                    'tamanho' => ['20cm', '25cm'],
                    'cor' => ['Branco', 'Bege']
                ]
            ],
            
            // MOUSE PADS E ACESSÓRIOS (7 produtos)
            [
                'name' => 'Mouse Pad Retangular',
                'category' => 'mouse_pads',
                'base_price' => 16.90,
                'cost_price' => 7.25,
                'variations' => 3,
                'variant_options' => [
                    'tamanho' => ['18x22cm', '20x24cm', '22x26cm'],
                    'espessura' => ['2mm', '3mm', '5mm']
                ]
            ],
            [
                'name' => 'Mouse Pad com Apoio',
                'category' => 'mouse_pads',
                'base_price' => 24.90,
                'cost_price' => 11.50,
                'variations' => 2,
                'variant_options' => [
                    'tamanho' => ['20x24cm', '22x26cm'],
                    'cor_apoio' => ['Preto', 'Azul', 'Verde']
                ]
            ],
            
            // PAPEL E MATERIAIS (5 produtos)
            [
                'name' => 'Papel Sublimático A4',
                'category' => 'papel',
                'base_price' => 89.90,
                'cost_price' => 42.50,
                'variations' => 2,
                'variant_options' => [
                    'gramatura' => ['90g', '120g'],
                    'quantidade' => ['100 folhas', '200 folhas', '500 folhas']
                ]
            ],
            [
                'name' => 'Tinta Sublimática',
                'category' => 'papel',
                'base_price' => 35.90,
                'cost_price' => 16.75,
                'variations' => 4,
                'variant_options' => [
                    'cor' => ['Amarelo', 'Magenta', 'Ciano', 'Preto'],
                    'volume' => ['100ml', '500ml', '1L']
                ]
            ]
        ];
    }
    
    private function createProduct($template, $categories, $faker, $productId)
    {
        $category = $categories[$template['category']];
        $variation = $template['variations'] > 1 ? $faker->numberBetween(1, $template['variations']) : '';
        
        $name = $template['name'] . ($variation ? " V{$variation}" : '');
        $sku = strtoupper(substr($template['category'], 0, 3)) . '-' . str_pad($productId, 3, '0', STR_PAD_LEFT);
        
        // Preço com pequena variação
        $basePrice = $template['base_price'];
        $priceVariation = $faker->numberBetween(-15, 20) / 100; // -15% a +20%
        $price = $basePrice + ($basePrice * $priceVariation);
        
        // Chance de produto promocional
        $isOnPromotion = $faker->boolean(30); // 30% de chance
        $promotionalPrice = null;
        $promotionStart = null;
        $promotionEnd = null;
        
        if ($isOnPromotion) {
            $discount = $faker->numberBetween(10, 40); // 10% a 40% de desconto
            $promotionalPrice = $price * (1 - $discount / 100);
            $promotionStart = now()->subDays($faker->numberBetween(1, 30));
            $promotionEnd = now()->addDays($faker->numberBetween(7, 60));
        }
        
        $descriptions = $this->getProductDescriptions($template['name'], $template['category']);
        
        return Product::create([
            'name' => $name,
            'description' => $descriptions['description'],
            'detailed_description' => $descriptions['detailed'],
            'short_description' => $descriptions['short'],
            'sku' => $sku,
            'barcode' => $faker->ean13(),
            'price' => round($price, 2),
            'promotional_price' => $promotionalPrice ? round($promotionalPrice, 2) : null,
            'promotion_start' => $promotionStart,
            'promotion_end' => $promotionEnd,
            'cost_price' => $template['cost_price'],
            'stock_quantity' => $faker->numberBetween(10, 200),
            'min_stock' => $faker->numberBetween(5, 20),
            'max_stock' => $faker->numberBetween(300, 1000),
            'min_stock_alert' => $faker->numberBetween(10, 30),
            'status' => 'active',
            'online_sale' => true,
            'is_customizable' => true,
            'weight' => $this->getProductWeight($template['category']),
            'length' => $faker->numberBetween(10, 50),
            'width' => $faker->numberBetween(8, 40),
            'height' => $faker->numberBetween(2, 30),
            'free_shipping' => $faker->boolean(20), // 20% de chance
            'brand' => $faker->randomElement(['Lumis', 'Premium', 'Quality', 'ProSub']),
            'featured' => $faker->boolean(25), // 25% de chance
            'rating' => $faker->randomFloat(1, 3.5, 5.0),
            'reviews_count' => $faker->numberBetween(0, 150),
            'category_id' => $category->id,
            'images' => [
                "https://picsum.photos/400/400?random={$productId}",
                "https://picsum.photos/400/400?random=" . ($productId + 1000),
                "https://picsum.photos/400/400?random=" . ($productId + 2000)
            ],
            'specifications' => $this->getProductSpecifications($template['category'], $faker)
        ]);
    }
    
    private function createProductVariants($product, $variantOptions, $faker)
    {
        $options = [];
        $optionCombinations = [];
        
        // Gerar todas as combinações possíveis
        foreach ($variantOptions as $optionName => $values) {
            $options[$optionName] = $values;
        }
        
        // Criar combinações
        $combinations = $this->generateCombinations($options);
        
        // Limitar a 20 variações por produto para não sobrecarregar
        $combinations = array_slice($combinations, 0, 20);
        
        foreach ($combinations as $combination) {
            $variantSku = ProductVariant::generateUniqueSku($product->sku, $combination);
            $variantName = $product->name . ' - ' . implode(' ', $combination);
            
            // Ajustes de preço baseados nas opções
            $priceAdjustment = $this->calculatePriceAdjustment($combination);
            $costAdjustment = $priceAdjustment * 0.6; // Custo acompanha 60% do ajuste de preço
            $weightAdjustment = $this->calculateWeightAdjustment($combination);
            
            ProductVariant::create([
                'product_id' => $product->id,
                'name' => $variantName,
                'sku' => $variantSku,
                'barcode' => $faker->ean13(),
                'option_values' => $combination,
                'price_adjustment' => $priceAdjustment,
                'cost_adjustment' => $costAdjustment,
                'stock_quantity' => $faker->numberBetween(5, 50),
                'min_stock' => $faker->numberBetween(2, 10),
                'weight_adjustment' => $weightAdjustment,
                'active' => true,
                'images' => [
                    "https://picsum.photos/400/400?random=" . ($product->id + 10000 + $faker->numberBetween(1, 1000))
                ]
            ]);
        }
    }
    
    private function generateCombinations($options)
    {
        $combinations = [[]];
        
        foreach ($options as $optionName => $values) {
            $newCombinations = [];
            foreach ($combinations as $combination) {
                foreach ($values as $value) {
                    $newCombination = $combination;
                    $newCombination[$optionName] = $value;
                    $newCombinations[] = $newCombination;
                }
            }
            $combinations = $newCombinations;
        }
        
        return $combinations;
    }
    
    private function calculatePriceAdjustment($combination)
    {
        $adjustment = 0;
        
        foreach ($combination as $option => $value) {
            switch ($option) {
                case 'tamanho':
                    $sizeAdjustments = [
                        'PP' => -2.00, 'P' => -1.00, 'M' => 0, 'G' => 1.00, 'GG' => 2.00, 'XG' => 3.00,
                        '2' => -3.00, '4' => -2.00, '6' => -1.00, '8' => 0, '10' => 1.00, '12' => 2.00,
                        '30x30cm' => 0, '35x35cm' => 3.00, '40x40cm' => 6.00,
                        '20x30cm' => 0, '30x40cm' => 5.00, '40x60cm' => 10.00, '50x70cm' => 15.00
                    ];
                    $adjustment += $sizeAdjustments[$value] ?? 0;
                    break;
                    
                case 'capacidade':
                    $capacityAdjustments = [
                        '300ml' => -1.00, '320ml' => 0, '325ml' => 0, '350ml' => 1.00, '400ml' => 2.00, '500ml' => 3.00
                    ];
                    $adjustment += $capacityAdjustments[$value] ?? 0;
                    break;
                    
                case 'acabamento':
                    if (in_array($value, ['Premium', 'Brilhante', 'Dourado'])) {
                        $adjustment += 2.00;
                    }
                    break;
                    
                case 'cor':
                    // Cores especiais custam mais
                    if (in_array($value, ['Dourado', 'Prateado', 'Cromado'])) {
                        $adjustment += 3.00;
                    }
                    break;
            }
        }
        
        return round($adjustment, 2);
    }
    
    private function calculateWeightAdjustment($combination)
    {
        $adjustment = 0;
        
        foreach ($combination as $option => $value) {
            switch ($option) {
                case 'tamanho':
                    if (strpos($value, 'GG') !== false || strpos($value, 'XG') !== false) {
                        $adjustment += 0.050; // 50g
                    } elseif (strpos($value, 'PP') !== false || strpos($value, 'P') !== false) {
                        $adjustment -= 0.030; // -30g
                    }
                    
                    // Tamanhos de produtos grandes
                    if (strpos($value, 'x') !== false) {
                        preg_match('/(\d+)x(\d+)/', $value, $matches);
                        if (isset($matches[1], $matches[2])) {
                            $area = (int)$matches[1] * (int)$matches[2];
                            $adjustment += $area * 0.00001; // Peso baseado na área
                        }
                    }
                    break;
                    
                case 'capacidade':
                    $volume = (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                    $adjustment += $volume * 0.0002; // 0.2g por ml
                    break;
            }
        }
        
        return round($adjustment, 3);
    }
    
    private function getProductDescriptions($name, $category)
    {
        $descriptions = [
            'canecas' => [
                'description' => 'Produto ideal para sublimação e personalização. Material de alta qualidade que garante excelente resultado na impressão.',
                'detailed' => 'Este produto foi especialmente desenvolvido para sublimação, oferecendo uma superfície perfeita para impressão. O material de alta qualidade garante durabilidade e cores vibrantes. Ideal para presentes personalizados, brindes corporativos e produtos promocionais.',
                'short' => 'Ideal para sublimação e personalização'
            ],
            'camisetas' => [
                'description' => 'Camiseta em poliéster 100% ideal para sublimação. Tecido macio e confortável com excelente absorção de tinta.',
                'detailed' => 'Camiseta confeccionada em poliéster 100%, material ideal para o processo de sublimação. O tecido oferece conforto, durabilidade e excelente fixação das cores. Perfeita para estampas personalizadas, uniformes e produtos promocionais.',
                'short' => 'Poliéster 100% para sublimação'
            ],
            'almofadas' => [
                'description' => 'Almofada com tecido especial para sublimação. Enchimento de fibra siliconada de alta qualidade.',
                'detailed' => 'Almofada produzida com tecido especialmente tratado para sublimação, garantindo cores vibrantes e duradouras. Enchimento em fibra siliconada que mantém o formato e oferece conforto. Ideal para decoração personalizada.',
                'short' => 'Tecido especial para sublimação'
            ]
        ];
        
        $default = [
            'description' => 'Produto de alta qualidade ideal para personalização e sublimação.',
            'detailed' => 'Produto desenvolvido com materiais de primeira linha, especialmente preparado para processos de sublimação e personalização. Garante excelente qualidade de impressão e durabilidade.',
            'short' => 'Ideal para personalização'
        ];
        
        return $descriptions[$category] ?? $default;
    }
    
    private function getProductWeight($category)
    {
        $weights = [
            'canecas' => [0.300, 0.400],
            'camisetas' => [0.150, 0.250],
            'almofadas' => [0.200, 0.500],
            'quadros' => [0.500, 1.500],
            'chaveiros' => [0.010, 0.050],
            'azulejos' => [0.200, 0.800],
            'mouse_pads' => [0.050, 0.150],
            'papel' => [0.100, 2.000]
        ];
        
        $range = $weights[$category] ?? [0.100, 0.500];
        return round(mt_rand($range[0] * 1000, $range[1] * 1000) / 1000, 3);
    }
    
    private function getProductSpecifications($category, $faker)
    {
        $specs = [
            'canecas' => [
                'material' => $faker->randomElement(['Porcelana', 'Cerâmica', 'Vidro', 'Alumínio']),
                'tipo_sublimacao' => 'Sublimação total',
                'temperatura_impressao' => '180°C',
                'tempo_impressao' => '60 segundos',
                'pressao' => '2,5 bar'
            ],
            'camisetas' => [
                'material' => 'Poliéster 100%',
                'gramatura' => $faker->randomElement(['150g/m²', '170g/m²', '180g/m²']),
                'tipo_sublimacao' => 'Sublimação total',
                'temperatura_impressao' => '200°C',
                'tempo_impressao' => '45 segundos'
            ],
            'almofadas' => [
                'material_capa' => $faker->randomElement(['Oxford', 'Sarja', 'Linho']),
                'enchimento' => 'Fibra siliconada',
                'tipo_sublimacao' => 'Sublimação frontal',
                'temperatura_impressao' => '180°C',
                'lavagem' => 'Máquina até 30°C'
            ]
        ];
        
        return $specs[$category] ?? [
            'material' => 'Especial para sublimação',
            'temperatura_impressao' => '180°C',
            'tempo_impressao' => '60 segundos'
        ];
    }
} 