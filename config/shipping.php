<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Shipping Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for shipping services and API integrations
    |
    */

    'default_service' => env('SHIPPING_DEFAULT_SERVICE', 'correios'),

    /*
    |--------------------------------------------------------------------------
    | Origin CEP
    |--------------------------------------------------------------------------
    |
    | The CEP (postal code) of the origin address where packages are shipped from
    |
    */

    'origin_cep' => env('SHIPPING_ORIGIN_CEP', '01310-100'),
    'origin_address' => env('SHIPPING_ORIGIN_ADDRESS', 'São Paulo, SP'),

    /*
    |--------------------------------------------------------------------------
    | Correios Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Brazilian Postal Service (Correios) API integration
    |
    */

    'correios' => [
        'enabled' => env('CORREIOS_ENABLED', true),
        'card_number' => env('CORREIOS_CARD_NUMBER'),
        'password' => env('CORREIOS_PASSWORD'),
        'contract_number' => env('CORREIOS_CONTRACT_NUMBER'),
        'user_code' => env('CORREIOS_USER_CODE'),
        'base_url' => env('CORREIOS_BASE_URL', 'https://api.correios.com.br'),
        
        'services' => [
            '04014' => 'Sedex',
            '04510' => 'PAC',
            '04782' => 'Sedex 12',
            '04790' => 'Sedex Hoje',
        ],
        
        'fallback_enabled' => env('CORREIOS_FALLBACK_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Package Configuration
    |--------------------------------------------------------------------------
    |
    | Default package dimensions and weight settings
    |
    */

    'package' => [
        'default_weight' => 0.5, // kg
        'default_length' => 20,  // cm
        'default_width' => 15,   // cm
        'default_height' => 5,   // cm
        'min_weight' => 0.3,     // kg
        'max_weight' => 30,      // kg
        'max_length' => 100,     // cm
        'max_width' => 100,      // cm
        'max_height' => 100,     // cm
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback Shipping Rates
    |--------------------------------------------------------------------------
    |
    | Used when API is unavailable or fails
    |
    */

    'fallback' => [
        'standard' => [
            'name' => 'Entrega Padrão',
            'price' => 19.90,
            'delivery_time' => 7,
        ],
        'express' => [
            'name' => 'Entrega Expressa',
            'price' => 29.90,
            'delivery_time' => 3,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Free Shipping
    |--------------------------------------------------------------------------
    |
    | Configuration for free shipping promotions
    |
    */

    'free_shipping' => [
        'enabled' => env('FREE_SHIPPING_ENABLED', true),
        'minimum_amount' => env('FREE_SHIPPING_MINIMUM', 299.00),
        'states' => env('FREE_SHIPPING_STATES', 'SP,RJ,MG'), // Comma-separated state codes
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Cache duration for shipping calculations (in seconds)
    |
    */

    'cache' => [
        'shipping_rates_ttl' => 3600,    // 1 hour
        'cep_data_ttl' => 86400,        // 24 hours
        'token_ttl' => 3000,            // 50 minutes
    ],

]; 