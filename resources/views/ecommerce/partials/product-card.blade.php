<div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden">
    <div class="relative">
        <!-- Product Image -->
        <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
            @if($product->main_image)
                <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <i class="ti ti-package text-gray-400 text-4xl"></i>
            @endif
        </div>
        
        <!-- Badges -->
        <div class="absolute top-2 left-2 flex flex-col space-y-1">
            @if($product->featured)
                <span class="bg-yellow-400 text-yellow-900 px-2 py-1 rounded text-xs font-semibold">
                    <i class="ti ti-star-filled mr-1"></i>Destaque
                </span>
            @endif
            
            @if($product->is_on_promotion)
                <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold">
                    -{{ $product->discount_percentage }}%
                </span>
            @endif
        </div>
        
        <!-- Stock Status -->
        <div class="absolute top-2 right-2">
            @if($product->stock_quantity <= 0)
                <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold">
                    Esgotado
                </span>
            @elseif($product->stock_quantity <= $product->min_stock_alert)
                <span class="bg-orange-500 text-white px-2 py-1 rounded text-xs font-semibold">
                    Últimas unidades
                </span>
            @endif
        </div>
    </div>
    
    <div class="p-4">
        <!-- Category -->
        @if($product->category)
            <span class="text-xs text-gray-500 uppercase tracking-wide">{{ $product->category->name }}</span>
        @endif
        
        <!-- Product Name -->
        <h3 class="font-semibold text-gray-900 mt-1 mb-2 line-clamp-2">
            <a href="{{ route('ecommerce.product', $product->id) }}" class="hover:text-blue-600 transition-colors">
                {{ $product->name }}
            </a>
        </h3>
        
        <!-- Description -->
        @if($product->short_description)
            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->short_description }}</p>
        @endif
        
        <!-- Rating -->
        @if($product->rating > 0)
            <div class="flex items-center mb-2">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $product->rating)
                            <i class="ti ti-star-filled text-yellow-400 text-sm"></i>
                        @else
                            <i class="ti ti-star text-gray-300 text-sm"></i>
                        @endif
                    @endfor
                </div>
                <span class="text-xs text-gray-500 ml-1">({{ $product->reviews_count }})</span>
            </div>
        @endif
        
        <!-- Price -->
        <div class="mb-4">
            @if($product->is_on_promotion)
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500 line-through">{{ $product->formatted_price }}</span>
                    <span class="text-lg font-bold text-green-600">{{ $product->formatted_promotional_price }}</span>
                </div>
            @else
                <span class="text-lg font-bold text-green-600">{{ $product->formatted_price }}</span>
            @endif
        </div>
        
        <!-- Actions -->
        <div class="flex space-x-2">
            <a href="{{ route('ecommerce.product', $product->id) }}" 
               class="flex-1 bg-gray-100 text-gray-700 py-2 px-4 rounded-lg text-center text-sm hover:bg-gray-200 transition-colors">
                Ver Detalhes
            </a>
            
            @if($product->stock_quantity > 0)
                <button onclick="addToCart({{ $product->id }}, 1)" 
                        class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                    <i class="ti ti-shopping-cart mr-1"></i>
                    Comprar
                </button>
            @else
                <button class="flex-1 bg-gray-300 text-gray-500 py-2 px-4 rounded-lg text-sm cursor-not-allowed" disabled>
                    Esgotado
                </button>
            @endif
        </div>
    </div>
</div> 