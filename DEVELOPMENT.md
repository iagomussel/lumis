# Guia de Desenvolvimento - Lumis ERP

Este documento contém informações técnicas para desenvolvedores que desejam contribuir ou expandir o sistema.

## 🏗️ Arquitetura do Sistema

### Estrutura MVC
O sistema segue o padrão MVC do Laravel:

- **Models**: Representam as entidades do banco (`app/Models/`)
- **Views**: Templates Blade para interface (`resources/views/`)
- **Controllers**: Lógica de negócio (`app/Http/Controllers/`)

### Organização de Controllers
```
app/Http/Controllers/
├── Admin/              # Controllers do painel administrativo
│   ├── DashboardController.php
│   ├── CategoryController.php
│   ├── ProductController.php
│   └── ...
└── Auth/               # Controllers de autenticação (Breeze)
```

### Models e Relacionamentos

#### Principais Relacionamentos:
- `Category` → `Product` (1:N)
- `Customer` → `Order` (1:N)
- `Order` → `OrderItem` (1:N)
- `Product` → `OrderItem` (1:N)
- `Supplier` → `Purchase` (1:N)
- `User` → `Lead` (1:N)

## 🛠️ Adicionando Novas Funcionalidades

### 1. Criando um Novo Módulo

#### Passo 1: Migration
```bash
php artisan make:migration create_nova_entidade_table
```

#### Passo 2: Model
```bash
php artisan make:model NovaEntidade
```

#### Passo 3: Controller
```bash
php artisan make:controller Admin/NovaEntidadeController --resource
```

#### Passo 4: Rotas
Adicione em `routes/web.php`:
```php
Route::resource('nova-entidade', NovaEntidadeController::class);
```

#### Passo 5: Views
Crie as views em `resources/views/admin/nova-entidade/`:
- `index.blade.php` (listagem)
- `create.blade.php` (formulário de criação)
- `edit.blade.php` (formulário de edição)
- `show.blade.php` (visualização)

### 2. Adicionando Item na Sidebar

Edite `resources/views/layouts/admin.blade.php`:

```html
<div class="px-6 py-1">
    <a href="{{ route('admin.nova-entidade.index') }}" 
       class="flex items-center text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.nova-entidade.*') ? 'bg-gray-700 text-white' : '' }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <!-- Ícone SVG -->
        </svg>
        Nova Entidade
    </a>
</div>
```

## 📊 Padrões de Desenvolvimento

### Models
Sempre inclua:
- `$fillable` para mass assignment
- `$casts` para conversão de tipos
- Relacionamentos Eloquent
- Scopes para consultas comuns
- Accessors para formatação

Exemplo:
```php
class Produto extends Model
{
    protected $fillable = ['name', 'price', 'status'];
    
    protected $casts = [
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];
    
    // Relacionamento
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    // Scope
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    // Accessor
    public function getFormattedPriceAttribute()
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }
}
```

### Controllers
Estrutura padrão para controllers de admin:

```php
class NovaEntidadeController extends Controller
{
    public function index()
    {
        $items = NovaEntidade::paginate(15);
        return view('admin.nova-entidade.index', compact('items'));
    }
    
    public function create()
    {
        return view('admin.nova-entidade.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // outras validações
        ]);
        
        NovaEntidade::create($validated);
        
        return redirect()->route('admin.nova-entidade.index')
            ->with('success', 'Item criado com sucesso!');
    }
    
    // show, edit, update, destroy...
}
```

### Views
Estrutura padrão para views:

```blade
@extends('layouts.admin')

@section('title', 'Título da Página')

@section('content')
<div class="space-y-6">
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold">Título</h2>
        <a href="{{ route('admin.nova-entidade.create') }}" 
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Novo Item
        </a>
    </div>
    
    <!-- Conteúdo -->
    <div class="bg-white shadow rounded-lg">
        <!-- Conteúdo da página -->
    </div>
</div>
@endsection
```

## 🎨 Componentes de Interface

### Cards de Estatísticas
```html
<div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="p-5">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                    <!-- Ícone -->
                </div>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Título</dt>
                    <dd class="text-lg font-medium text-gray-900">Valor</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
```

### Tabelas Responsivas
```html
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Coluna
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <!-- Linhas -->
        </tbody>
    </table>
</div>
```

### Badges de Status
```html
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
    @if($status === 'active') bg-green-100 text-green-800
    @elseif($status === 'inactive') bg-red-100 text-red-800
    @else bg-gray-100 text-gray-800
    @endif">
    {{ ucfirst($status) }}
</span>
```

## 🔧 Utilitários e Helpers

### Formatação de Moeda
```php
// No Model
public function getFormattedPriceAttribute()
{
    return 'R$ ' . number_format($this->price, 2, ',', '.');
}

// Na View
{{ $product->formatted_price }}
```

### Geração Automática de Números
```php
// No Model (método boot)
protected static function boot()
{
    parent::boot();
    
    static::creating(function ($model) {
        if (!$model->numero) {
            $model->numero = 'PRE-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
        }
    });
}
```

## 📝 Validações Comuns

### Request Validation
```php
$request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:customers,email',
    'price' => 'required|numeric|min:0',
    'status' => 'required|in:active,inactive',
    'image' => 'nullable|image|max:2048',
]);
```

### Custom Form Requests
```bash
php artisan make:request StoreProductRequest
```

## 🧪 Testes

### Criando Testes
```bash
php artisan make:test ProductTest
```

### Estrutura de Teste
```php
class ProductTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_can_create_product()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post('/admin/products', [
                'name' => 'Produto Teste',
                'price' => 100.00,
                'category_id' => Category::factory()->create()->id,
            ]);
            
        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['name' => 'Produto Teste']);
    }
}
```

## 🚀 Deploy e Produção

### Comandos de Deploy
```bash
# Atualizar dependências
composer install --no-dev --optimize-autoloader

# Compilar assets
npm run build

# Executar migrations
php artisan migrate --force

# Limpar caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Configurações de Produção
No `.env` de produção:
```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 📚 Recursos Úteis

### Documentação
- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)

### Comandos Úteis
```bash
# Limpar todos os caches
php artisan optimize:clear

# Recriar banco (desenvolvimento)
php artisan migrate:fresh --seed

# Gerar factory
php artisan make:factory ProductFactory

# Gerar seeder
php artisan make:seeder ProductSeeder
```

## 🐛 Debug e Troubleshooting

### Laravel Telescope (Desenvolvimento)
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

### Log Viewer
```bash
composer require rap2hpoutre/laravel-log-viewer
```

### Debug Bar
```bash
composer require barryvdh/laravel-debugbar --dev
```

---

**Happy Coding! 🚀** 