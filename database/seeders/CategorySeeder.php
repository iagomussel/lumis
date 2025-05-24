<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categorias de Insumos
        $insumos = Category::create([
            'name' => 'Insumos',
            'description' => 'Materiais e insumos para produção',
            'type' => 'insumo',
            'slug' => Str::slug('Insumos'),
            'active' => true,
        ]);

        Category::create([
            'name' => 'Matéria Prima',
            'description' => 'Materiais básicos para produção',
            'type' => 'insumo',
            'slug' => Str::slug('Matéria Prima'),
            'active' => true,
            'parent_id' => $insumos->id,
        ]);

        Category::create([
            'name' => 'Embalagens',
            'description' => 'Materiais para embalagem',
            'type' => 'insumo',
            'slug' => Str::slug('Embalagens'),
            'active' => true,
            'parent_id' => $insumos->id,
        ]);

        // Categorias de Ativos
        $ativos = Category::create([
            'name' => 'Ativos',
            'description' => 'Equipamentos e ativos da empresa',
            'type' => 'ativo',
            'slug' => Str::slug('Ativos'),
            'active' => true,
        ]);

        Category::create([
            'name' => 'Equipamentos',
            'description' => 'Máquinas e equipamentos',
            'type' => 'ativo',
            'slug' => Str::slug('Equipamentos'),
            'active' => true,
            'parent_id' => $ativos->id,
        ]);

        Category::create([
            'name' => 'Móveis e Utensílios',
            'description' => 'Móveis e utensílios de escritório',
            'type' => 'ativo',
            'slug' => Str::slug('Móveis e Utensílios'),
            'active' => true,
            'parent_id' => $ativos->id,
        ]);

        Category::create([
            'name' => 'Veículos',
            'description' => 'Veículos da empresa',
            'type' => 'ativo',
            'slug' => Str::slug('Veículos'),
            'active' => true,
            'parent_id' => $ativos->id,
        ]);
    }
}
