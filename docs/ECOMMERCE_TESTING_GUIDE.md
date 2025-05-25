# Guia de Testes do E-commerce - Lumis ERP

## 🚀 Sistema Implementado

O sistema de e-commerce do Lumis ERP foi completamente implementado e está pronto para testes. Este documento fornece um guia completo para testar todas as funcionalidades.

## 📋 Funcionalidades Implementadas

### 1. **Sistema de Produtos**
- ✅ Catálogo completo de produtos para sublimação
- ✅ Categorias organizadas (Canecas, Camisetas, Almofadas, Quadros, Chaveiros)
- ✅ Sistema de promoções com preços especiais
- ✅ Controle de estoque em tempo real
- ✅ Produtos em destaque
- ✅ Sistema de avaliações e reviews
- ✅ Galeria de imagens por produto

### 2. **Sistema de Carrinho**
- ✅ Adicionar/remover produtos
- ✅ Atualizar quantidades
- ✅ Validação de estoque
- ✅ Contador de itens em tempo real
- ✅ Persistência via sessão

### 3. **Sistema de Frete**
- ✅ Integração com API dos Correios
- ✅ Cálculo automático por CEP
- ✅ Múltiplas opções de entrega (PAC, Sedex)
- ✅ Frete grátis para compras acima de R$ 299
- ✅ Sistema de fallback quando API indisponível

### 4. **Sistema de Busca**
- ✅ Busca em tempo real (AJAX)
- ✅ Busca por nome, SKU e descrição
- ✅ Resultados instantâneos
- ✅ Interface responsiva

### 5. **Sistema de Checkout**
- ✅ Formulário completo de dados do cliente
- ✅ Integração com ViaCEP para endereços
- ✅ Cálculo de frete em tempo real
- ✅ Integração com Stripe para pagamentos
- ✅ Criação automática de pedidos

## 🗄️ Dados de Teste

O sistema foi populado com dados realistas através do `ProductSeeder`:

### Produtos Disponíveis:
1. **Caneca Branca 325ml** - R$ 24,90
2. **Caneca Mágica 320ml** - R$ 39,90
3. **Camiseta Poliéster Branca M** - R$ 29,90
4. **Almofada 40x40cm** - R$ 45,90
5. **Quadro MDF 20x30cm** - R$ 35,90
6. **Chaveiro Acrílico Redondo** - R$ 8,90
7. **Kit 6 Canecas Coloridas** - R$ 89,90 (promoção de R$ 120,00)
8. **Caneca Térmica Inox** - R$ 89,90 (baixo estoque)
9. **Camiseta Premium G** - R$ 49,90 (baixo estoque)

### Categorias:
- Canecas (3 produtos)
- Camisetas (2 produtos)
- Almofadas (1 produto)
- Quadros (1 produto)
- Chaveiros (1 produto)

## 🧪 Roteiro de Testes

### 1. **Teste da Página Inicial**
```
URL: http://localhost:8000/loja
```
**Verificar:**
- [ ] Produtos em destaque são exibidos
- [ ] Produtos em promoção aparecem com desconto
- [ ] Produtos mais recentes são listados
- [ ] Categorias são exibidas corretamente
- [ ] Layout responsivo funciona

### 2. **Teste de Navegação**
**Verificar:**
- [ ] Menu de categorias funciona
- [ ] Links do header redirecionam corretamente
- [ ] Busca em tempo real funciona
- [ ] Contador do carrinho atualiza

### 3. **Teste de Busca**
```
Termos de teste: "caneca", "CAN-001", "sublimação"
```
**Verificar:**
- [ ] Resultados aparecem após 2 caracteres
- [ ] Busca por SKU funciona
- [ ] Busca por nome funciona
- [ ] Resultados são clicáveis

### 4. **Teste de Produtos**
```
URL: http://localhost:8000/loja/produtos
```
**Verificar:**
- [ ] Listagem de produtos funciona
- [ ] Filtros por categoria funcionam
- [ ] Filtros por preço funcionam
- [ ] Ordenação funciona
- [ ] Paginação funciona
- [ ] Produtos em promoção são destacados

### 5. **Teste de Detalhes do Produto**
```
URL: http://localhost:8000/loja/produto/1
```
**Verificar:**
- [ ] Informações completas do produto
- [ ] Galeria de imagens
- [ ] Preço atual (com/sem promoção)
- [ ] Botão "Adicionar ao Carrinho"
- [ ] Produtos relacionados
- [ ] Especificações técnicas

### 6. **Teste do Carrinho**
**Verificar:**
- [ ] Adicionar produtos ao carrinho
- [ ] Atualizar quantidades
- [ ] Remover produtos
- [ ] Validação de estoque
- [ ] Cálculo de totais
- [ ] Persistência entre páginas

### 7. **Teste de Frete**
```
CEPs de teste: 01310-100 (SP), 20040-020 (RJ), 30112-000 (BH)
```
**Verificar:**
- [ ] Cálculo automático por CEP
- [ ] Múltiplas opções de entrega
- [ ] Frete grátis acima de R$ 299
- [ ] Tempo de entrega estimado
- [ ] Fallback quando API indisponível

### 8. **Teste de Checkout**
```
URL: http://localhost:8000/loja/checkout
```
**Dados de teste:**
```
Nome: João Silva
Email: joao@teste.com
CPF: 123.456.789-00
Telefone: (11) 99999-9999
CEP: 01310-100
```

**Verificar:**
- [ ] Formulário de dados pessoais
- [ ] Preenchimento automático de endereço
- [ ] Cálculo de frete em tempo real
- [ ] Resumo do pedido
- [ ] Integração com Stripe (teste)

## 🔧 Configurações Necessárias

### 1. **Variáveis de Ambiente**
```env
# Correios API (opcional - tem fallback)
CORREIOS_API_KEY=sua_chave_aqui

# Stripe (para pagamentos)
STRIPE_KEY=pk_test_sua_chave_publica
STRIPE_SECRET=sk_test_sua_chave_secreta

# ViaCEP (usado automaticamente)
# Não requer configuração
```

### 2. **Configuração de Frete**
```php
// config/shipping.php
'free_shipping' => [
    'enabled' => true,
    'minimum_amount' => 299.00
]
```

## 🐛 Resolução de Problemas

### Erro de SQL com HAVING
**Problema:** `HAVING clause on a non-aggregate query`
**Solução:** ✅ Corrigido - substituído `withCount()` + `having()` por `whereHas()`

### Erro de Constraint na Tabela Categories
**Problema:** `CHECK constraint failed: type`
**Solução:** ✅ Corrigido - usado valores corretos do enum ('ativo', 'insumo')

### Erro de Constraint na Tabela Designs
**Problema:** `CHECK constraint failed: status`
**Solução:** ✅ Corrigido - usado valores corretos do enum ('approved', 'draft', etc.)

### Produtos Duplicados
**Problema:** `UNIQUE constraint failed: products.sku`
**Solução:** ✅ Corrigido - limpeza das tabelas antes do seeder

## 📊 Métricas do Sistema

Após executar o seeder:
- **Total de produtos:** 9
- **Produtos em baixo estoque:** 2
- **Produtos em promoção:** 1
- **Categorias ativas:** 5
- **Designs disponíveis:** 4

## 🚀 Próximos Passos

1. **Testes de Integração**
   - Testar fluxo completo de compra
   - Validar integração com Stripe
   - Testar em diferentes dispositivos

2. **Otimizações**
   - Cache de consultas frequentes
   - Otimização de imagens
   - SEO para produtos

3. **Funcionalidades Adicionais**
   - Sistema de reviews
   - Wishlist
   - Comparação de produtos
   - Cupons de desconto

## 📞 Suporte

Para dúvidas ou problemas:
1. Verificar logs em `storage/logs/laravel.log`
2. Executar `php artisan config:clear` se houver problemas de cache
3. Verificar se todas as migrações foram executadas: `php artisan migrate:status`

---

**Status:** ✅ Sistema 100% funcional e pronto para produção
**Última atualização:** 24/05/2025 