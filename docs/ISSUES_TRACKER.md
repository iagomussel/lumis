# Issues Tracker - Lumis ERP

## 🚨 Erros Críticos (PDV)

### 1. PDV - Busca de Produtos não funciona
- **Status**: ✅ Corrigido
- **Prioridade**: Alta
- **Descrição**: A funcionalidade de buscar produtos no PDV não estava funcionando
- **Arquivo relacionado**: `resources/views/admin/pos/index.blade.php`
- **Controller**: `app/Http/Controllers/Admin/PosController.php`
- **Correções aplicadas**:
  - ✅ JavaScript robusto com logs de debug
  - ✅ Verificação de elementos DOM
  - ✅ Headers CSRF corretos
  - ✅ Tratamento de erros melhorado
  - ✅ Indicadores visuais de loading
- **Teste criado**: `/admin/pos/test` para verificar AJAX
- **Data**: 2025-05-25

### 2. PDV - Busca de Cliente não funciona  
- **Status**: ✅ Corrigido
- **Prioridade**: Alta
- **Descrição**: A funcionalidade de buscar clientes no PDV não estava funcionando
- **Arquivo relacionado**: `resources/views/admin/pos/index.blade.php`
- **Controller**: `app/Http/Controllers/Admin/POSController.php`
- **Correções aplicadas**:
  - ✅ JavaScript robusto com logs de debug
  - ✅ Verificação de elementos DOM
  - ✅ Headers CSRF corretos
  - ✅ Tratamento de erros melhorado
  - ✅ Indicadores visuais de loading
  - ✅ Interface visual aprimorada com ícones
  - ✅ Seleção dinâmica no dropdown principal
- **Teste criado**: `/admin/pos/test-customers` para verificar AJAX
- **Data**: 2025-05-25

## 💰 Funcionalidades de Pagamento

### 3. PDV - Pagamento Parcial (Sinal)
- **Status**: ✅ Corrigido
- **Prioridade**: Alta
- **Descrição**: Cliente deve poder pagar apenas 50% (sinal) e ficar com 50% de saldo para pagar na entrega
- **Impacto**: Fluxo de caixa e controle financeiro
- **Tabelas relacionadas**: `orders`, `financial_transactions`, `account_receivables`
- **Correções aplicadas**:
  - ✅ Checkbox para ativar pagamento parcial
  - ✅ Campo para inserir valor do sinal
  - ✅ Botão "50%" para sugerir metade do valor
  - ✅ Validação de valores (não pode ser maior que total)
  - ✅ Cálculo automático do saldo restante
  - ✅ Integração com contas a receber
  - ✅ Status de pedido "partially_paid"
  - ✅ Mensagem diferenciada no sucesso
  - ✅ Limpeza automática dos campos ao limpar carrinho
- **Data**: 2025-05-25

### 4. Página de Valores a Receber
- **Status**: 🔴 Pendente
- **Prioridade**: Alta
- **Descrição**: Sistema precisa ter uma página dedicada para controlar valores a receber
- **Arquivo a criar**: `resources/views/admin/receivables/`
- **Controller a criar**: `app/Http/Controllers/Admin/ReceivablesController.php`

### 5. Agendamento de Entrega - Informações Financeiras
- **Status**: 🔴 Pendente
- **Prioridade**: Média
- **Descrição**: Ao agendar entrega, mostrar valor do sinal e valor do saldo restante
- **Arquivo relacionado**: Views de agendamento de entrega

## 🛠️ Erros de Navegação

### 6. Erro ao acessar Fornecedores
- **Status**: 🔴 Pendente  
- **Prioridade**: Média
- **Descrição**: Erro ao tentar acessar a página de fornecedores
- **Arquivo relacionado**: `app/Http/Controllers/Admin/SupplierController.php`
- **Views relacionadas**: `resources/views/admin/suppliers/`

## 📋 Páginas Faltantes

### 7. Página de Leads
- **Status**: 🔴 Pendente
- **Prioridade**: Média
- **Descrição**: Interface para gerenciamento de leads não existe
- **Arquivos a criar**: 
  - `app/Http/Controllers/Admin/LeadController.php`
  - `resources/views/admin/leads/`
  - Adicionar rota e menu

### 8. Página de Purchases (Compras)
- **Status**: 🔴 Pendente
- **Prioridade**: Média
- **Descrição**: Interface para gerenciamento de compras não existe
- **Arquivos a criar**:
  - `app/Http/Controllers/Admin/PurchaseController.php` 
  - `resources/views/admin/purchases/`
  - Adicionar rota e menu

### 9. Página de Estoque
- **Status**: 🔴 Pendente
- **Prioridade**: Alta
- **Descrição**: Interface para controle de estoque não existe
- **Arquivos a criar**:
  - `app/Http/Controllers/Admin/InventoryController.php`
  - `resources/views/admin/inventory/`
  - Adicionar rota e menu

## 🎨 Issues de Design

### 10. Login Customer - Identidade Visual Inconsistente
- **Status**: 🔴 Pendente
- **Prioridade**: Média
- **Descrição**: A tela de login do customer deve ter a mesma identidade visual da loja (e-commerce)
- **Problema identificado**: 
  - Login customer usa layout `layouts.public` básico
  - Loja usa layout `layouts.ecommerce` com branding completo
  - Cores, fontes e gradientes diferentes entre as duas experiências
- **Arquivos relacionados**: 
  - `resources/views/auth/customer/login.blade.php`
  - `resources/views/auth/customer/register.blade.php`
  - `resources/views/layouts/ecommerce.blade.php`
  - `resources/css/branding.css`
- **Solução sugerida**:
  - Migrar login/register para layout `ecommerce`
  - Aplicar classe `lumis-hero-gradient` no background
  - Usar variáveis CSS de branding (`--store-primary`, `--store-secondary`)
  - Manter header com logo da loja
  - Consistência com cores: `#2563eb` (primary) e `#7c3aed` (secondary)
- **Data identificado**: 2025-05-25

## 📊 Resumo por Prioridade

### 🔥 Alta Prioridade (Crítico)
1. PDV - Busca de Produtos
2. PDV - Busca de Cliente  
3. PDV - Pagamento Parcial
4. Página de Valores a Receber
5. Página de Estoque

### ⚡ Média Prioridade
6. Agendamento de Entrega - Info Financeiras
7. Erro ao acessar Fornecedores
8. Página de Leads
9. Página de Purchases

## 🎯 Próximos Passos

**Sugestão de ordem de correção:**
1. ✅ Corrigir busca de produtos no PDV
2. ✅ Corrigir busca de clientes no PDV  
3. ✅ Implementar sistema de pagamento parcial
4. ✅ Criar página de valores a receber
5. ✅ Criar interface de controle de estoque
6. ✅ Corrigir erro dos fornecedores
7. ✅ Criar páginas faltantes (Leads, Purchases)

---

**Última atualização**: 25/05/2025
**Total de issues**: 10
**Issues críticas**: 5 