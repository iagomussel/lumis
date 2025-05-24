<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    private $client;
    private $cepOrigin;
    private $cepRegex = '/^[0-9]{5}-?[0-9]{3}$/';

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 30,
            'headers' => [
                'User-Agent' => 'Lumis ERP/1.0',
                'Accept' => 'application/json'
            ]
        ]);
        
        $this->cepOrigin = config('shipping.origin_cep', '01310-100'); // São Paulo - SP
    }

    /**
     * Calculate shipping cost using Correios API
     */
    public function calculateShipping($cepDestination, $weight = 1, $length = 20, $height = 5, $width = 15)
    {
        try {
            // Validate and clean CEP
            $cepDestination = $this->cleanCep($cepDestination);
            if (!$this->isValidCep($cepDestination)) {
                throw new \Exception('CEP de destino inválido');
            }

            // Cache key for shipping calculation
            $cacheKey = "shipping_{$this->cepOrigin}_{$cepDestination}_{$weight}_{$length}_{$height}_{$width}";
            
            return Cache::remember($cacheKey, 3600, function() use ($cepDestination, $weight, $length, $height, $width) {
                return $this->requestShippingRates($cepDestination, $weight, $length, $height, $width);
            });

        } catch (\Exception $e) {
            Log::error('Shipping calculation error: ' . $e->getMessage());
            return $this->getFallbackShippingRates();
        }
    }

    /**
     * Request shipping rates from Correios API
     */
    private function requestShippingRates($cepDestination, $weight, $length, $height, $width)
    {
        // Usando a nova API dos Correios (REST)
        $url = 'https://api.correios.com.br/preco/v1/nacional';
        
        $params = [
            'cepOrigem' => $this->cleanCep($this->cepOrigin),
            'cepDestino' => $this->cleanCep($cepDestination),
            'peso' => $weight,
            'comprimento' => $length,
            'altura' => $height,
            'largura' => $width,
            'diametro' => 0,
            'formato' => 1, // Caixa/pacote
            'maoPropria' => false,
            'valorDeclarado' => 0,
            'avisoRecebimento' => false
        ];

        try {
            $response = $this->client->get($url, [
                'query' => $params,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getCorreiosToken()
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return $this->parseCorreiosResponse($data);

        } catch (\Exception $e) {
            // Fallback para ViaCEP + cálculo estimado
            return $this->calculateEstimatedShipping($cepDestination, $weight);
        }
    }

    /**
     * Get Correios authentication token
     */
    private function getCorreiosToken()
    {
        return Cache::remember('correios_token', 3000, function() {
            try {
                $response = $this->client->post('https://api.correios.com.br/token/v1/autentica/cartaopostagem', [
                    'json' => [
                        'numero' => config('shipping.correios.card_number'),
                        'senha' => config('shipping.correios.password')
                    ]
                ]);

                $data = json_decode($response->getBody(), true);
                return $data['token'] ?? null;

            } catch (\Exception $e) {
                Log::error('Error getting Correios token: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Parse Correios API response
     */
    private function parseCorreiosResponse($data)
    {
        $results = [];
        
        if (isset($data['servicos']) && is_array($data['servicos'])) {
            foreach ($data['servicos'] as $service) {
                if (isset($service['erro']) && $service['erro'] === '0') {
                    $results[] = [
                        'service_code' => $service['codigo'],
                        'service_name' => $this->getServiceName($service['codigo']),
                        'price' => (float) str_replace(',', '.', $service['valor']),
                        'delivery_time' => (int) $service['prazoEntrega'],
                        'error' => null
                    ];
                }
            }
        }

        if (empty($results)) {
            return $this->getFallbackShippingRates();
        }

        return $results;
    }

    /**
     * Calculate estimated shipping using ViaCEP for distance
     */
    private function calculateEstimatedShipping($cepDestination, $weight)
    {
        try {
            // Get origin and destination data
            $originData = $this->getCepData($this->cepOrigin);
            $destData = $this->getCepData($cepDestination);

            if (!$originData || !$destData) {
                return $this->getFallbackShippingRates();
            }

            // Estimate based on state
            $sameState = $originData['uf'] === $destData['uf'];
            $basePrice = $sameState ? 15.00 : 25.00;
            
            // Weight multiplier
            $weightMultiplier = max(1, ceil($weight));
            
            return [
                [
                    'service_code' => 'PAC',
                    'service_name' => 'PAC - Sedex',
                    'price' => $basePrice * $weightMultiplier,
                    'delivery_time' => $sameState ? 3 : 7,
                    'error' => null
                ],
                [
                    'service_code' => 'SEDEX',
                    'service_name' => 'Sedex',
                    'price' => ($basePrice * 1.5) * $weightMultiplier,
                    'delivery_time' => $sameState ? 1 : 3,
                    'error' => null
                ]
            ];

        } catch (\Exception $e) {
            return $this->getFallbackShippingRates();
        }
    }

    /**
     * Get CEP data from ViaCEP
     */
    private function getCepData($cep)
    {
        $cacheKey = "cep_data_{$cep}";
        
        return Cache::remember($cacheKey, 86400, function() use ($cep) {
            try {
                $response = $this->client->get("https://viacep.com.br/ws/{$cep}/json/");
                $data = json_decode($response->getBody(), true);
                
                return isset($data['erro']) ? null : $data;
                
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    /**
     * Get fallback shipping rates when APIs fail
     */
    private function getFallbackShippingRates()
    {
        return [
            [
                'service_code' => 'STANDARD',
                'service_name' => 'Entrega Padrão',
                'price' => 19.90,
                'delivery_time' => 7,
                'error' => null
            ],
            [
                'service_code' => 'EXPRESS',
                'service_name' => 'Entrega Expressa',
                'price' => 29.90,
                'delivery_time' => 3,
                'error' => null
            ]
        ];
    }

    /**
     * Get service name by code
     */
    private function getServiceName($code)
    {
        $services = [
            '04014' => 'Sedex',
            '04510' => 'PAC',
            '04782' => 'Sedex 12',
            '04790' => 'Sedex Hoje',
            '40169' => 'Sedex 12',
            '40215' => 'Sedex 10'
        ];

        return $services[$code] ?? "Serviço {$code}";
    }

    /**
     * Validate CEP format
     */
    private function isValidCep($cep)
    {
        return preg_match($this->cepRegex, $cep);
    }

    /**
     * Clean CEP removing non-numeric characters
     */
    private function cleanCep($cep)
    {
        $cleaned = preg_replace('/[^0-9]/', '', $cep);
        return strlen($cleaned) === 8 ? substr($cleaned, 0, 5) . '-' . substr($cleaned, 5) : $cleaned;
    }

    /**
     * Calculate product dimensions based on cart items
     */
    public function calculateCartDimensions($cartItems)
    {
        $totalWeight = 0;
        $totalVolume = 0;
        
        foreach ($cartItems as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];
            
            // Get product weight (default 0.5kg if not set)
            $weight = $product->weight ?? 0.5;
            $totalWeight += $weight * $quantity;
            
            // Get product dimensions (default 20x15x5 cm if not set)
            $length = $product->length ?? 20;
            $width = $product->width ?? 15;
            $height = $product->height ?? 5;
            $totalVolume += ($length * $width * $height * $quantity);
        }
        
        // Calculate final package dimensions
        $packageLength = max(20, min(100, ceil(sqrt($totalVolume / 5))));
        $packageWidth = max(15, min(100, ceil($packageLength * 0.75)));
        $packageHeight = max(5, min(100, ceil($totalVolume / ($packageLength * $packageWidth))));
        
        return [
            'weight' => max(0.3, $totalWeight), // Minimum 300g
            'length' => $packageLength,
            'width' => $packageWidth,
            'height' => $packageHeight
        ];
    }

    /**
     * Get shipping options for checkout
     */
    public function getShippingOptions($cepDestination, $cartItems)
    {
        $dimensions = $this->calculateCartDimensions($cartItems);
        
        return $this->calculateShipping(
            $cepDestination,
            $dimensions['weight'],
            $dimensions['length'],
            $dimensions['height'],
            $dimensions['width']
        );
    }
} 