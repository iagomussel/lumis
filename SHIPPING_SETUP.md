# 🚚 CONFIGURAÇÃO DE FRETE - INTEGRAÇÃO CORREIOS

## 📋 **VISÃO GERAL**

Sistema integrado de cálculo de frete com:
- ✅ API dos Correios (oficial)
- ✅ Fallback com estimativa baseada em CEP
- ✅ Frete grátis configurável
- ✅ Cache de consultas para performance
- ✅ Múltiplas opções de entrega

---

## ⚙️ **CONFIGURAÇÃO INICIAL**

### **1. Variáveis de Ambiente**

Adicione no seu arquivo `.env`:

```env
# Shipping Configuration
SHIPPING_DEFAULT_SERVICE=correios
SHIPPING_ORIGIN_CEP=01310-100
SHIPPING_ORIGIN_ADDRESS="São Paulo, SP"

# Correios API Configuration
CORREIOS_ENABLED=true
CORREIOS_CARD_NUMBER=your_card_number
CORREIOS_PASSWORD=your_password
CORREIOS_CONTRACT_NUMBER=your_contract
CORREIOS_USER_CODE=your_user_code
CORREIOS_FALLBACK_ENABLED=true

# Free Shipping Configuration
FREE_SHIPPING_ENABLED=true
FREE_SHIPPING_MINIMUM=299.00
FREE_SHIPPING_STATES="SP,RJ,MG"
```

### **2. Credenciais dos Correios**

Para obter as credenciais dos Correios:

1. **Acesse:** https://www.correios.com.br/
2. **Cadastre-se** no portal de serviços
3. **Contrate** um cartão de postagem
4. **Obtenha** as credenciais:
   - Número do cartão de postagem
   - Senha do contrato
   - Código do usuário
   - Número do contrato

---

## 🔧 **ESTRUTURA IMPLEMENTADA**

### **Serviço de Frete (`ShippingService`)**

```php
// Calcula frete com múltiplas opções
$shipping = new ShippingService();
$options = $shipping->getShippingOptions($cep, $cartItems);

// Retorna array com:
[
    [
        'service_code' => 'SEDEX',
        'service_name' => 'Sedex',
        'price' => 25.90,
        'delivery_time' => 3,
        'formatted_price' => 'R$ 25,90'
    ],
    // ... mais opções
]
```

### **Campos de Produto**

Novos campos adicionados à tabela `products`:
- `length` - Comprimento em cm
- `width` - Largura em cm  
- `height` - Altura em cm
- `free_shipping` - Frete grátis para este produto
- `shipping_notes` - Observações especiais

### **Rota de Cálculo**

```javascript
// POST /loja/shipping/calculate
{
    "cep": "01234-567"
}

// Response:
{
    "success": true,
    "free_shipping": false,
    "options": [...]
}
```

---

## 💡 **FUNCIONALIDADES**

### **✅ Implementado**

1. **Cálculo Real de Frete**
   - Integração oficial Correios
   - PAC, Sedex e variações
   - Baseado em peso e dimensões

2. **Frete Grátis Inteligente**
   - Por valor mínimo do pedido
   - Por estados específicos
   - Por produtos especiais

3. **Fallback Robusto**
   - Quando API está indisponível
   - Estimativa baseada em distância
   - Cache para performance

4. **Interface Moderna**
   - Cálculo em tempo real no checkout
   - Múltiplas opções de entrega
   - Feedback visual de loading

### **⚠️ Limitações Atuais**

1. **Cadastro de Dimensões**
   - Produtos precisam ter dimensões cadastradas
   - Valores padrão são aplicados se não informado

2. **API Correios**
   - Requer contrato com Correios
   - Pode ter instabilidade ocasional

---

## 🎯 **COMO USAR**

### **1. No Cadastro de Produtos**

```php
// Ao criar/editar produtos, informe:
$product = Product::create([
    'name' => 'Caneca Personalizada',
    'price' => 29.90,
    'weight' => 0.3,        // kg
    'length' => 12,         // cm
    'width' => 8,           // cm  
    'height' => 9,          // cm
    'free_shipping' => false,
    // ... outros campos
]);
```

### **2. No Checkout**

O sistema automaticamente:
1. ✅ Detecta o CEP inserido
2. ✅ Calcula dimensões da embalagem
3. ✅ Consulta APIs de frete
4. ✅ Exibe opções disponíveis
5. ✅ Atualiza totais em tempo real

### **3. No Pedido Final**

```php
// O pedido é criado com informações de frete:
$order = Order::create([
    'subtotal' => 150.00,
    'shipping' => 19.90,
    'total' => 169.90,
    'shipping_address_json' => [
        // ... endereço
        'shipping_service' => 'SEDEX',
        'delivery_time' => 3
    ]
]);
```

---

## 🔄 **FLUXO COMPLETO**

### **1. Cliente no Checkout**
```
1. Insere CEP → 2. Endereço preenchido automaticamente
3. Sistema calcula frete → 4. Opções exibidas
5. Cliente escolhe → 6. Total atualizado
```

### **2. Cálculo de Frete**
```
1. Dimensões dos produtos → 2. Embalagem calculada
3. Consulta Correios → 4. Fallback se necessário
5. Cache resultado → 6. Retorna opções
```

### **3. Tratamento de Erros**
```
1. API indisponível → 2. Fallback ativo
3. CEP inválido → 4. Mensagem de erro
5. Sem opções → 6. Frete padrão
```

---

## 📊 **EXEMPLOS DE USO**

### **Produto Pequeno (Chaveiro)**
```
Peso: 0.1kg | Dimensões: 5x3x1cm
SP → RJ: PAC R$ 8,50 | Sedex R$ 12,90
```

### **Produto Médio (Caneca)**
```
Peso: 0.3kg | Dimensões: 12x8x9cm  
SP → RS: PAC R$ 15,90 | Sedex R$ 22,50
```

### **Produto Grande (Quadro)**
```
Peso: 1.2kg | Dimensões: 40x30x5cm
SP → AM: PAC R$ 28,90 | Sedex R$ 45,00
```

---

## 🚀 **PRÓXIMOS PASSOS**

### **Configuração Recomendada:**

1. **Obter credenciais Correios**
2. **Configurar CEP de origem**
3. **Definir política de frete grátis**
4. **Cadastrar dimensões dos produtos**
5. **Testar em ambiente de homologação**

### **Melhorias Futuras:**

- [ ] Integração com transportadoras privadas
- [ ] Cálculo de prazo mais preciso
- [ ] Seguro automático
- [ ] Rastreamento integrado

---

**📅 Data de Implementação:** 24/05/2025  
**👨‍💻 Desenvolvedor:** Senior Laravel Developer  
**✅ Status:** Pronto para Produção 