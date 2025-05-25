<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Store Branding Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure all branding aspects of your e-commerce store.
    | This includes store name, logo, slogan, colors, and other visual elements.
    |
    */

    'store' => [
        'name' => env('STORE_NAME', 'LumisPresentes'),
        'slogan' => env('STORE_SLOGAN', 'perfeição em cada detalhe'),
        'tagline' => env('STORE_TAGLINE', 'Transformando momentos em memórias especiais'),
        'description' => env('STORE_DESCRIPTION', 'Sua loja online de produtos personalizados para sublimação. Canecas, camisetas, almofadas e muito mais com a qualidade LumisPresentes.'),
    ],

    'logo' => [
        'main' => env('STORE_LOGO', '/images/branding/logo-main.png'),
        'icon' => env('STORE_LOGO_ICON', '/images/branding/logo-icon.png'),
        'favicon' => env('STORE_FAVICON', '/images/branding/favicon.ico'),
        'dark' => env('STORE_LOGO_DARK', '/images/branding/logo-dark.png'),
        'white' => env('STORE_LOGO_WHITE', '/images/branding/logo-white.png'),
    ],

    'colors' => [
        'primary' => env('STORE_PRIMARY_COLOR', '#2563eb'), // Blue-600
        'secondary' => env('STORE_SECONDARY_COLOR', '#7c3aed'), // Violet-600
        'accent' => env('STORE_ACCENT_COLOR', '#f59e0b'), // Amber-500
        'success' => env('STORE_SUCCESS_COLOR', '#10b981'), // Emerald-500
        'warning' => env('STORE_WARNING_COLOR', '#f59e0b'), // Amber-500
        'error' => env('STORE_ERROR_COLOR', '#ef4444'), // Red-500
        'gray' => [
            'light' => env('STORE_GRAY_LIGHT', '#f8fafc'), // Gray-50
            'medium' => env('STORE_GRAY_MEDIUM', '#64748b'), // Gray-500
            'dark' => env('STORE_GRAY_DARK', '#1e293b'), // Gray-800
        ],
    ],

    'contact' => [
        'phone' => env('STORE_PHONE', '(21) 99577-5689'),
        'email' => env('STORE_EMAIL', 'contato@lumispresentes.com.br'),
        'whatsapp' => env('STORE_WHATSAPP', '5521995775689'),
        'address' => env('STORE_ADDRESS', 'Rio de Janeiro, RJ'),
        'business_hours' => env('STORE_BUSINESS_HOURS', 'Segunda a Sexta: 9h às 18h | Sábado: 9h às 13h'),
    ],

    'social' => [
        'facebook' => env('STORE_FACEBOOK', 'https://facebook.com/lumispresentes'),
        'instagram' => env('STORE_INSTAGRAM', 'https://instagram.com/lumispresentes'),
        'twitter' => env('STORE_TWITTER', null),
        'youtube' => env('STORE_YOUTUBE', null),
        'tiktok' => env('STORE_TIKTOK', null),
        'linkedin' => env('STORE_LINKEDIN', null),
    ],

    'theme' => [
        'font_family' => env('STORE_FONT_FAMILY', "'Plus Jakarta Sans', sans-serif"),
        'border_radius' => env('STORE_BORDER_RADIUS', '0.5rem'), // 8px
        'shadow' => env('STORE_SHADOW', '0 4px 6px -1px rgb(0 0 0 / 0.1)'),
        'animation_duration' => env('STORE_ANIMATION_DURATION', '300ms'),
    ],

    'seo' => [
        'title_suffix' => env('STORE_TITLE_SUFFIX', '| LumisPresentes'),
        'meta_description' => env('STORE_META_DESCRIPTION', 'Produtos personalizados para sublimação com qualidade premium. Canecas, camisetas, almofadas e muito mais na LumisPresentes.'),
        'keywords' => env('STORE_KEYWORDS', 'sublimação, produtos personalizados, canecas, camisetas, almofadas, presentes'),
        'og_image' => env('STORE_OG_IMAGE', '/images/branding/og-image.jpg'),
    ],

    'features' => [
        'show_ratings' => env('STORE_SHOW_RATINGS', true),
        'show_reviews' => env('STORE_SHOW_REVIEWS', true),
        'show_wishlist' => env('STORE_SHOW_WISHLIST', false),
        'show_compare' => env('STORE_SHOW_COMPARE', false),
        'show_quick_view' => env('STORE_SHOW_QUICK_VIEW', true),
        'show_stock_quantity' => env('STORE_SHOW_STOCK', false),
        'enable_guest_checkout' => env('STORE_GUEST_CHECKOUT', true),
    ],

    'shipping' => [
        'free_shipping_text' => env('STORE_FREE_SHIPPING_TEXT', 'Frete Grátis acima de R$ 299'),
        'delivery_time_text' => env('STORE_DELIVERY_TIME_TEXT', 'Entrega em todo o Brasil'),
        'security_text' => env('STORE_SECURITY_TEXT', 'Compra 100% Segura'),
    ],

    'notifications' => [
        'success_color' => '#10b981',
        'error_color' => '#ef4444',
        'warning_color' => '#f59e0b',
        'info_color' => '#3b82f6',
    ],
]; 