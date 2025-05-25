# 🎨 GUIA DE BRANDING - LumisPresentes

## 📋 **SISTEMA DE BRANDING CONFIGURÁVEL**

O sistema LumisPresentes agora possui um sistema de branding completamente configurável que permite personalizar todos os aspectos visuais da loja e-commerce.

## 🎯 **CONFIGURAÇÕES DISPONÍVEIS**

### **1. IDENTIDADE DA MARCA**

```php
// config/branding.php
'store' => [
    'name' => 'LumisPresentes',
    'slogan' => 'perfeição em cada detalhe',
    'tagline' => 'Transformando momentos em memórias especiais',
    'description' => 'Sua loja online de produtos personalizados...',
],
```

### **2. LOGOS E IMAGENS**

```php
'logo' => [
    'main' => '/images/branding/logo-main.svg',      // Logo principal
    'icon' => '/images/branding/logo-icon.svg',      // Ícone pequeno
    'favicon' => '/images/branding/logo-icon.svg',   // Favicon
    'dark' => '/images/branding/logo-main.svg',      // Versão escura
    'white' => '/images/branding/logo-main.svg',     // Versão branca
],
```

### **3. PALETA DE CORES**

```php
'colors' => [
    'primary' => '#2563eb',    // Azul principal
    'secondary' => '#7c3aed',  // Roxo secundário
    'accent' => '#f59e0b',     // Âmbar destaque
    'success' => '#10b981',    // Verde sucesso
    'warning' => '#f59e0b',    // Amarelo alerta
    'error' => '#ef4444',      // Vermelho erro
],
```

### **4. CONTATO E REDES SOCIAIS**

```php
'contact' => [
    'phone' => '(21) 99577-5689',
    'email' => 'contato@lumispresentes.com.br',
    'whatsapp' => '5521995775689',
    'address' => 'Rio de Janeiro, RJ',
    'business_hours' => 'Segunda a Sexta: 9h às 18h | Sábado: 9h às 13h',
],

'social' => [
    'facebook' => 'https://facebook.com/lumispresentes',
    'instagram' => 'https://instagram.com/lumispresentes',
    'twitter' => null,
    // ...
],
```

## 🛠️ **COMO PERSONALIZAR**

### **Método 1: Arquivo .env**

Adicione as variáveis no arquivo `.env`:

```env
STORE_NAME="LumisPresentes"
STORE_SLOGAN="perfeição em cada detalhe"
STORE_PRIMARY_COLOR="#2563eb"
STORE_SECONDARY_COLOR="#7c3aed"
STORE_ACCENT_COLOR="#f59e0b"
STORE_PHONE="(21) 99577-5689"
STORE_EMAIL="contato@lumispresentes.com.br"
STORE_WHATSAPP="5521995775689"
STORE_FACEBOOK="https://facebook.com/lumispresentes"
STORE_INSTAGRAM="https://instagram.com/lumispresentes"
```

### **Método 2: Arquivo config/branding.php**

Edite diretamente o arquivo de configuração:

```php
'store' => [
    'name' => 'Sua Loja',
    'slogan' => 'Seu Slogan Aqui',
    // ...
],
```

## 🎨 **LOGOS E IMAGENS**

### **Formatos Suportados:**
- ✅ **SVG** (Recomendado) - Escalável e editável
- ✅ **PNG** - Transparência
- ✅ **JPG** - Fotografias
- ✅ **WebP** - Moderna e otimizada

### **Dimensões Recomendadas:**

| Tipo | Tamanho | Uso |
|------|---------|-----|
| **Logo Principal** | 160x40px | Cabeçalho principal |
| **Ícone** | 48x48px | Favicon, mobile |
| **Logo Footer** | 120x30px | Rodapé |
| **OG Image** | 1200x630px | Redes sociais |

### **Estrutura de Pastas:**

```
public/images/branding/
├── logo-main.svg          # Logo principal
├── logo-icon.svg          # Ícone da marca
├── logo-dark.svg          # Versão escura
├── logo-white.svg         # Versão branca
├── favicon.ico            # Favicon
└── og-image.jpg           # Open Graph
```

## 🎨 **CSS PERSONALIZADO**

### **Variáveis CSS Geradas Automaticamente:**

```css
:root {
    --store-primary: #2563eb;
    --store-secondary: #7c3aed;
    --store-accent: #f59e0b;
    --store-font-family: 'Plus Jakarta Sans', sans-serif;
}
```

### **Classes Utilitárias:**

```css
.brand-primary        { color: var(--store-primary); }
.brand-bg-primary     { background-color: var(--store-primary); }
.brand-border-primary { border-color: var(--store-primary); }

.lumis-btn-primary    { Botão com estilo da marca }
.lumis-card          { Card com estilo da marca }
.lumis-badge         { Badge com estilo da marca }
```

## 📱 **RESPONSIVIDADE**

O sistema de branding é totalmente responsivo:

- ✅ **Desktop** - Logo completo
- ✅ **Tablet** - Logo adaptado
- ✅ **Mobile** - Ícone compacto
- ✅ **PWA** - Ícones apropriados

## 🔧 **DESENVOLVIMENTO**

### **Compilar Assets:**

```bash
npm run build    # Produção
npm run dev      # Desenvolvimento
```

### **Limpar Cache:**

```bash
php artisan config:cache
php artisan view:cache
```

### **Adicionar Nova Cor:**

1. **Atualize config/branding.php:**
```php
'colors' => [
    'nova_cor' => '#ff0000',
],
```

2. **Adicione ao CSS:**
```css
.brand-nova-cor { color: var(--store-nova-cor); }
```

3. **Use no template:**
```blade
<div class="brand-nova-cor">Texto colorido</div>
```

## 🚀 **CASOS DE USO**

### **1. Mudança de Marca Completa:**
- Altere todas as configurações no arquivo `config/branding.php`
- Substitua logos na pasta `public/images/branding/`
- Execute `php artisan config:cache`

### **2. Promoção Sazonal:**
- Altere cores temporariamente no `.env`
- Mantenha logos principais
- Reverta depois da promoção

### **3. Multi-tenant:**
- Diferentes configurações por domínio
- Logos específicos por loja
- Cores personalizadas por cliente

## 📊 **SEO E REDES SOCIAIS**

### **Meta Tags Automáticas:**

```html
<title>Página | LumisPresentes</title>
<meta name="description" content="...">
<meta property="og:title" content="...">
<meta property="og:image" content="/images/branding/og-image.jpg">
```

### **Estrutura de Dados:**

```json
{
  "@type": "Store",
  "name": "LumisPresentes",
  "description": "perfeição em cada detalhe",
  "url": "https://lumispresentes.com.br",
  "logo": "/images/branding/logo-main.svg"
}
```

## ✅ **CHECKLIST DE IMPLEMENTAÇÃO**

### **Básico:**
- [ ] Nome da loja configurado
- [ ] Slogan definido
- [ ] Cores principais escolhidas
- [ ] Logo principal criado
- [ ] Favicon configurado

### **Avançado:**
- [ ] Logo para tema escuro
- [ ] Imagem Open Graph
- [ ] Redes sociais configuradas
- [ ] Contato atualizado
- [ ] Horário de funcionamento

### **Otimização:**
- [ ] Logos otimizados (SVG)
- [ ] Cores acessíveis (contraste)
- [ ] Fonts carregadas
- [ ] Cache configurado

## 🎯 **EXEMPLO COMPLETO**

### **LumisPresentes - Configuração Atual:**

```php
return [
    'store' => [
        'name' => 'LumisPresentes',
        'slogan' => 'perfeição em cada detalhe',
        'tagline' => 'Transformando momentos em memórias especiais',
        'description' => 'Produtos personalizados para sublimação...',
    ],
    
    'colors' => [
        'primary' => '#2563eb',    // Azul confiança
        'secondary' => '#7c3aed',  // Roxo criatividade  
        'accent' => '#f59e0b',     // Âmbar energia
    ],
    
    'contact' => [
        'phone' => '(21) 99577-5689',
        'email' => 'contato@lumispresentes.com.br',
        'whatsapp' => '5521995775689',
    ],
    
    'shipping' => [
        'free_shipping_text' => 'Frete Grátis acima de R$ 299',
        'security_text' => 'Compra 100% Segura',
    ],
];
```

## 📞 **SUPORTE**

Para dúvidas sobre personalização:

- 📧 **Email:** dev@lumispresentes.com.br
- 📱 **WhatsApp:** (21) 99577-5689
- 📂 **Documentação:** `/docs/branding.md`

---

**Desenvolvido para LumisPresentes** - Sistema de Branding v1.0  
*"perfeição em cada detalhe"* ✨ 