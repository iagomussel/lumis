<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sistema ERP - Configurações
    |--------------------------------------------------------------------------
    |
    | Configurações específicas do sistema ERP Lumis
    |
    */

    'name' => env('ERP_NAME', 'Lumis ERP'),
    'version' => '1.0.0',
    'company' => env('ERP_COMPANY', 'Lumis presentes'),

    /*
    |--------------------------------------------------------------------------
    | Configurações de Produtos
    |--------------------------------------------------------------------------
    */
    'products' => [
        'default_status' => 'active',
        'auto_generate_sku' => true,
        'sku_prefix' => 'PRD',
        'image_max_size' => 2048, // KB
        'allowed_image_types' => ['jpg', 'jpeg', 'png', 'gif'],
        'allowed_file_types' => ['pdf', 'doc', 'docx', 'xls', 'xlsx'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Pedidos
    |--------------------------------------------------------------------------
    */
    'orders' => [
        'number_prefix' => 'PED',
        'auto_confirm' => false,
        'default_payment_terms' => 30,
        'tax_rate' => 0.0, // Percentual de imposto padrão
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Compras
    |--------------------------------------------------------------------------
    */
    'purchases' => [
        'quotation_prefix' => 'COT',
        'purchase_order_prefix' => 'COM',
        'default_payment_terms' => 30,
        'quote_validity_days' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Leads
    |--------------------------------------------------------------------------
    */
    'leads' => [
        'default_score' => 0,
        'high_score_threshold' => 70,
        'follow_up_reminder_days' => 7,
        'auto_assign' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Estoque
    |--------------------------------------------------------------------------
    */
    'inventory' => [
        'low_stock_alert' => true,
        'auto_update_stock' => true,
        'negative_stock_allowed' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Email
    |--------------------------------------------------------------------------
    */
    'email' => [
        'from_name' => env('ERP_EMAIL_FROM_NAME', 'Lumis ERP'),
        'from_address' => env('ERP_EMAIL_FROM_ADDRESS', 'noreply@lumiserp.com'),
        'notifications' => [
            'low_stock' => true,
            'new_order' => true,
            'order_status_change' => true,
            'lead_assigned' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Dashboard
    |--------------------------------------------------------------------------
    */
    'dashboard' => [
        'recent_items_limit' => 5,
        'low_stock_limit' => 10,
        'stats_cache_minutes' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Paginação
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        'per_page' => 15,
        'max_per_page' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Upload
    |--------------------------------------------------------------------------
    */
    'uploads' => [
        'disk' => 'public',
        'max_file_size' => 10240, // KB
        'image_quality' => 85,
        'thumbnail_size' => [150, 150],
    ],
]; 