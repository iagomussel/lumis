<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Design;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have categories first
        $categories = [
            'canecas' => Category::firstOrCreate(['name' => 'Canecas'], [
                'description' => 'Canecas personalizadas para sublimação',
                'type' => 'ativo',
                'active' => true,
                'slug' => 'canecas'
            ]),
            'camisetas' => Category::firstOrCreate(['name' => 'Camisetas'], [
                'description' => 'Camisetas para sublimação',
                'type' => 'ativo',
                'active' => true,
                'slug' => 'camisetas'
            ]),
            'almofadas' => Category::firstOrCreate(['name' => 'Almofadas'], [
                'description' => 'Almofadas personalizadas',
                'type' => 'ativo',
                'active' => true,
                'slug' => 'almofadas'
            ]),
            'quadros' => Category::firstOrCreate(['name' => 'Quadros'], [
                'description' => 'Quadros e placas decorativas',
                'type' => 'ativo',
                'active' => true,
                'slug' => 'quadros'
            ]),
            'chaveiros' => Category::firstOrCreate(['name' => 'Chaveiros'], [
                'description' => 'Chaveiros personalizados',
                'type' => 'ativo',
                'active' => true,
                'slug' => 'chaveiros'
            ])
        ];

        // Create sample designs first
        // Get or create a default user for designs
        $defaultUser = \App\Models\User::first();
        if (!$defaultUser) {
            $defaultUser = \App\Models\User::create([
                'name' => 'Sistema',
                'email' => 'sistema@lumiserp.com',
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
            ]);
        }

        $designs = [
            Design::firstOrCreate(['name' => 'Logo Empresa'], [
                'description' => 'Logo corporativo padrão',
                'file_path' => '/designs/logo-empresa.ai',
                'file_name' => 'logo-empresa.ai',
                'file_type' => 'ai',
                'file_size' => 2048,
                'status' => 'approved',
                'is_template' => false,
                'is_public' => true,
                'version' => '1.0',
                'usage_count' => 0,
                'created_by' => $defaultUser->id,
                'approved_by' => $defaultUser->id,
                'approved_at' => now()
            ]),
            Design::firstOrCreate(['name' => 'Feliz Aniversário'], [
                'description' => 'Design para aniversários',
                'file_path' => '/designs/feliz-aniversario.psd',
                'file_name' => 'feliz-aniversario.psd',
                'file_type' => 'psd',
                'file_size' => 4096,
                'status' => 'approved',
                'is_template' => true,
                'is_public' => true,
                'version' => '1.0',
                'usage_count' => 0,
                'created_by' => $defaultUser->id,
                'approved_by' => $defaultUser->id,
                'approved_at' => now()
            ]),
            Design::firstOrCreate(['name' => 'Dia das Mães'], [
                'description' => 'Design especial para o Dia das Mães',
                'file_path' => '/designs/dia-das-maes.png',
                'file_name' => 'dia-das-maes.png',
                'file_type' => 'png',
                'file_size' => 1024,
                'status' => 'approved',
                'is_template' => true,
                'is_public' => true,
                'version' => '1.0',
                'usage_count' => 0,
                'created_by' => $defaultUser->id,
                'approved_by' => $defaultUser->id,
                'approved_at' => now()
            ]),
            Design::firstOrCreate(['name' => 'Motivacional'], [
                'description' => 'Frases motivacionais',
                'file_path' => '/designs/motivacional.svg',
                'file_name' => 'motivacional.svg',
                'file_type' => 'svg',
                'file_size' => 512,
                'status' => 'approved',
                'is_template' => true,
                'is_public' => true,
                'version' => '1.0',
                'usage_count' => 0,
                'created_by' => $defaultUser->id,
                'approved_by' => $defaultUser->id,
                'approved_at' => now()
            ])
        ];

        // Products data with realistic sublimation items
        $products = [
            // Canecas
            [
                'name' => 'Caneca Branca 325ml',
                'description' => 'Caneca de porcelana branca para sublimação',
                'detailed_description' => 'Caneca de porcelana branca de alta qualidade, ideal para sublimação. Capacidade de 325ml, formato cilíndrico tradicional.',
                'short_description' => 'Caneca branca 325ml para personalização',
                'sku' => 'CAN-001',
                'barcode' => '7891234567890',
                'price' => 24.90,
                'cost_price' => 12.50,
                'stock_quantity' => 150,
                'min_stock' => 20,
                'max_stock' => 500,
                'min_stock_alert' => 30,
                'status' => 'active',
                'online_sale' => true,
                'is_customizable' => true,
                'weight' => 0.350,
                'length' => 12,
                'width' => 8,
                'height' => 9,
                'free_shipping' => false,
                'brand' => 'Lumis',
                'featured' => true,
                'rating' => 4.8,
                'reviews_count' => 127,
                'category_id' => $categories['canecas']->id,
                'images' => ['/images/products/caneca-branca-01.jpg', '/images/products/caneca-branca-02.jpg'],
                'specifications' => [
                    'material' => 'Porcelana',
                    'capacidade' => '325ml',
                    'cor' => 'Branco',
                    'formato' => 'Cilíndrico'
                ]
            ],
            [
                'name' => 'Caneca Mágica 320ml',
                'description' => 'Caneca que muda de cor com calor',
                'detailed_description' => 'Caneca mágica que revela a imagem impressa quando em contato com líquido quente. Perfeita para presentes especiais.',
                'short_description' => 'Caneca mágica que muda de cor',
                'sku' => 'CAN-002',
                'barcode' => '7891234567891',
                'price' => 39.90,
                'cost_price' => 18.75,
                'stock_quantity' => 85,
                'min_stock' => 15,
                'max_stock' => 300,
                'min_stock_alert' => 25,
                'status' => 'active',
                'online_sale' => true,
                'is_customizable' => true,
                'weight' => 0.380,
                'length' => 12,
                'width' => 8,
                'height' => 9,
                'free_shipping' => false,
                'brand' => 'Lumis',
                'featured' => true,
                'rating' => 4.9,
                'reviews_count' => 89,
                'category_id' => $categories['canecas']->id,
                'images' => ['/images/products/caneca-magica-01.jpg', '/images/products/caneca-magica-02.jpg'],
                'specifications' => [
                    'material' => 'Cerâmica especial',
                    'capacidade' => '320ml',
                    'cor' => 'Preto/Branco',
                    'efeito' => 'Termossensível'
                ]
            ],

            // Camisetas
            [
                'name' => 'Camiseta Poliéster Branca M',
                'description' => 'Camiseta 100% poliéster para sublimação',
                'detailed_description' => 'Camiseta confeccionada em 100% poliéster, ideal para sublimação. Tecido de alta qualidade que garante cores vivas e duradouras.',
                'short_description' => 'Camiseta poliéster branca tamanho M',
                'sku' => 'CAM-001-M',
                'barcode' => '7891234567892',
                'price' => 29.90,
                'cost_price' => 15.20,
                'stock_quantity' => 200,
                'min_stock' => 30,
                'max_stock' => 800,
                'min_stock_alert' => 50,
                'status' => 'active',
                'online_sale' => true,
                'is_customizable' => true,
                'weight' => 0.180,
                'length' => 30,
                'width' => 25,
                'height' => 2,
                'free_shipping' => false,
                'brand' => 'Lumis',
                'featured' => true,
                'rating' => 4.7,
                'reviews_count' => 156,
                'category_id' => $categories['camisetas']->id,
                'images' => ['/images/products/camiseta-branca-m-01.jpg'],
                'specifications' => [
                    'material' => '100% Poliéster',
                    'tamanho' => 'M',
                    'cor' => 'Branco',
                    'gola' => 'Careca'
                ]
            ],

            // Almofadas
            [
                'name' => 'Almofada 40x40cm',
                'description' => 'Almofada quadrada para sublimação',
                'detailed_description' => 'Almofada decorativa quadrada de 40x40cm com capa em tecido poliéster ideal para sublimação. Inclui enchimento de fibra.',
                'short_description' => 'Almofada quadrada 40x40cm',
                'sku' => 'ALM-001',
                'barcode' => '7891234567893',
                'price' => 45.90,
                'cost_price' => 22.80,
                'stock_quantity' => 75,
                'min_stock' => 10,
                'max_stock' => 200,
                'min_stock_alert' => 20,
                'status' => 'active',
                'online_sale' => true,
                'is_customizable' => true,
                'weight' => 0.450,
                'length' => 40,
                'width' => 40,
                'height' => 10,
                'free_shipping' => false,
                'brand' => 'Lumis',
                'featured' => false,
                'rating' => 4.6,
                'reviews_count' => 73,
                'category_id' => $categories['almofadas']->id,
                'images' => ['/images/products/almofada-40x40-01.jpg'],
                'specifications' => [
                    'material' => 'Poliéster',
                    'dimensoes' => '40x40cm',
                    'enchimento' => 'Fibra siliconizada',
                    'formato' => 'Quadrado'
                ]
            ],

            // Quadros
            [
                'name' => 'Quadro MDF 20x30cm',
                'description' => 'Quadro em MDF com impressão sublimática',
                'detailed_description' => 'Quadro decorativo em MDF revestido para sublimação. Tamanho 20x30cm, espessura 3mm, com acabamento brilhante.',
                'short_description' => 'Quadro MDF 20x30cm',
                'sku' => 'QUA-001',
                'barcode' => '7891234567894',
                'price' => 35.90,
                'cost_price' => 16.50,
                'stock_quantity' => 120,
                'min_stock' => 15,
                'max_stock' => 400,
                'min_stock_alert' => 25,
                'status' => 'active',
                'online_sale' => true,
                'is_customizable' => true,
                'weight' => 0.250,
                'length' => 30,
                'width' => 20,
                'height' => 0.3,
                'free_shipping' => false,
                'brand' => 'Lumis',
                'featured' => true,
                'rating' => 4.5,
                'reviews_count' => 92,
                'category_id' => $categories['quadros']->id,
                'images' => ['/images/products/quadro-mdf-20x30-01.jpg'],
                'specifications' => [
                    'material' => 'MDF revestido',
                    'dimensoes' => '20x30cm',
                    'espessura' => '3mm',
                    'acabamento' => 'Brilhante'
                ]
            ],

            // Chaveiros
            [
                'name' => 'Chaveiro Acrílico Redondo',
                'description' => 'Chaveiro em acrílico cristal para sublimação',
                'detailed_description' => 'Chaveiro redondo em acrílico cristal transparente, ideal para sublimação. Diâmetro de 5cm com argola metálica.',
                'short_description' => 'Chaveiro acrílico redondo 5cm',
                'sku' => 'CHA-001',
                'barcode' => '7891234567895',
                'price' => 8.90,
                'cost_price' => 3.50,
                'stock_quantity' => 500,
                'min_stock' => 100,
                'max_stock' => 2000,
                'min_stock_alert' => 150,
                'status' => 'active',
                'online_sale' => true,
                'is_customizable' => true,
                'weight' => 0.025,
                'length' => 5,
                'width' => 5,
                'height' => 0.3,
                'free_shipping' => true,
                'brand' => 'Lumis',
                'featured' => false,
                'rating' => 4.4,
                'reviews_count' => 234,
                'category_id' => $categories['chaveiros']->id,
                'images' => ['/images/products/chaveiro-acrilico-redondo-01.jpg'],
                'specifications' => [
                    'material' => 'Acrílico cristal',
                    'formato' => 'Redondo',
                    'diametro' => '5cm',
                    'argola' => 'Metal'
                ]
            ],

            // Produtos com promoção
            [
                'name' => 'Kit 6 Canecas Coloridas',
                'description' => 'Kit promocional com 6 canecas coloridas',
                'detailed_description' => 'Kit especial contendo 6 canecas de porcelana em cores variadas (azul, verde, amarelo, rosa, laranja, roxo). Ideal para personalização em família.',
                'short_description' => 'Kit 6 canecas coloridas',
                'sku' => 'KIT-001',
                'barcode' => '7891234567896',
                'price' => 120.00,
                'promotional_price' => 89.90,
                'promotion_start' => now()->subDays(5),
                'promotion_end' => now()->addDays(10),
                'cost_price' => 60.00,
                'stock_quantity' => 45,
                'min_stock' => 8,
                'max_stock' => 150,
                'min_stock_alert' => 15,
                'status' => 'active',
                'online_sale' => true,
                'is_customizable' => true,
                'weight' => 2.100,
                'length' => 25,
                'width' => 20,
                'height' => 12,
                'free_shipping' => true,
                'brand' => 'Lumis',
                'featured' => true,
                'rating' => 4.9,
                'reviews_count' => 67,
                'category_id' => $categories['canecas']->id,
                'images' => ['/images/products/kit-6-canecas-01.jpg', '/images/products/kit-6-canecas-02.jpg'],
                'specifications' => [
                    'quantidade' => '6 unidades',
                    'cores' => 'Variadas',
                    'material' => 'Porcelana',
                    'capacidade' => '320ml cada'
                ]
            ]
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);

            // Assign random designs to products
            $numDesigns = rand(1, min(3, count($designs)));
            $randomDesignKeys = array_rand($designs, $numDesigns);
            if (!is_array($randomDesignKeys)) {
                $randomDesignKeys = [$randomDesignKeys];
            }
            
            foreach ($randomDesignKeys as $index => $key) {
                $design = $designs[$key];
                $product->designs()->attach($design->id, [
                    'is_default' => $index === 0, // First design is default
                    'design_notes' => 'Design aplicável para ' . $product->name
                ]);
            }

            $this->command->info("Created product: {$product->name}");
        }

        // Create some products with low stock for testing
        $lowStockProducts = [
            [
                'name' => 'Caneca Térmica Inox',
                'description' => 'Caneca térmica em aço inox',
                'sku' => 'CAN-TERM-001',
                'price' => 89.90,
                'cost_price' => 45.00,
                'stock_quantity' => 3, // Low stock
                'min_stock' => 10,
                'max_stock' => 100,
                'status' => 'active',
                'online_sale' => true,
                'is_customizable' => true,
                'weight' => 0.500,
                'length' => 15,
                'width' => 10,
                'height' => 18,
                'category_id' => $categories['canecas']->id,
                'rating' => 4.7,
                'reviews_count' => 45
            ],
            [
                'name' => 'Camiseta Premium G',
                'description' => 'Camiseta premium tamanho G',
                'sku' => 'CAM-PREM-G',
                'price' => 49.90,
                'cost_price' => 25.00,
                'stock_quantity' => 2, // Low stock
                'min_stock' => 15,
                'max_stock' => 200,
                'status' => 'active',
                'online_sale' => true,
                'is_customizable' => true,
                'weight' => 0.220,
                'length' => 35,
                'width' => 30,
                'height' => 2,
                'category_id' => $categories['camisetas']->id,
                'rating' => 4.8,
                'reviews_count' => 23
            ]
        ];

        foreach ($lowStockProducts as $productData) {
            $product = Product::create($productData);
            
            // Assign one random design
            $randomKey = array_rand($designs);
            $design = $designs[$randomKey];
            $product->designs()->attach($design->id, [
                'is_default' => true,
                'design_notes' => 'Design padrão para produto em baixo estoque'
            ]);

            $this->command->info("Created low stock product: {$product->name}");
        }

        $this->command->info('Products seeded successfully!');
        $this->command->info('Total products created: ' . Product::count());
        $this->command->info('Products with low stock: ' . Product::lowStock()->count());
        $this->command->info('Products on promotion: ' . Product::onPromotion()->count());
    }
}
