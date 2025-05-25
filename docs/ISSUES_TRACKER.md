# Issues Tracker - Lumis ERP

## 🚨 Erros Críticos (PDV)

### 1. PDV - Busca de Produtos não funciona
- **Status**: 🔴 Pendente
- **Prioridade**: Alta
- **Descrição**: A funcionalidade de buscar produtos no PDV, ao digitar nada acontece
- **Arquivo relacionado**: `resources/views/admin/pos/index.blade.php`
- **Controller**: `app/Http/Controllers/Admin/PosController.php`
- **Problema identificado**:
  - JavaScript está implementado corretamente
  - Route existe e controller funciona
  - Possível problema de autenticação ou JavaScript não executando
  - Endpoint retorna "Unauthenticated" quando testado sem login
- **Investigação necessária**:
  - Verificar se usuário está logado corretamente
  - Verificar console do browser para erros JavaScript
  - Verificar se CSRF token está sendo enviado
  - Verificar se elementos DOM estão sendo encontrados
- **Correções aplicadas**:
  - ✅ JavaScript robusto com logs de debug
  - ✅ Verificação de elementos DOM
  - ✅ Headers CSRF corretos
  - ✅ Tratamento de erros melhorado
  - ✅ Indicadores visuais de loading
- **Teste criado**: `/admin/pos/test` para verificar AJAX
- **Data**: 2025-05-25

### 2. PDV - Busca de Cliente não funciona  
- **Status**: 🔴 Pendente
- **Prioridade**: Alta
- **Descrição**: A funcionalidade de buscar clientes no PDV não estava funcionando
- **Arquivo relacionado**: `resources/views/admin/pos/index.blade.php`
- **Controller**: `app/Http/Controllers/Admin/POSController.php`
- **Problema similar ao #1**:
  - Mesmo problema de autenticação/JavaScript
  - Código implementado corretamente
  - Necessita investigação de autenticação
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
- **Status**: 🔴 Verificar
- **Prioridade**: Alta
- **Descrição**: Cliente deve poder pagar apenas 50% (sinal) e ficar com 50% de saldo para pagar na entrega
- **Impacto**: Fluxo de caixa e controle financeiro
- **Tabelas relacionadas**: `orders`, `financial_transactions`, `account_receivables`
- **Status atual**: Implementado mas precisa verificação
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
- **Necessita verificação**:
  - Testar fluxo completo de pagamento parcial
  - Verificar se conta a receber é criada corretamente
  - Verificar se saldo é calculado corretamente
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
  - Usar variáveis CSS de branding (`--store-primary`, `--store-accent`)
  - Garantir consistência visual com o e-commerce

### 11. View de Edição de Categorias Não Encontrada
- **Status**: 🔴 Pendente
- **Prioridade**: Alta
- **Descrição**: View `admin.categories.edit` não existe, causando erro 404 ao tentar editar categorias
- **URL problemática**: `/admin/categories/{id}/edit`
- **Erro**: `View [admin.categories.edit] not found`
- **Problema identificado**:
  - Rota existe e direciona corretamente
  - Controller provavelmente tem método `edit()`
  - Falta apenas criar a view `resources/views/admin/categories/edit.blade.php`
- **Solução necessária**:
  - Criar view de edição com formulário
  - Incluir validação frontend
  - Botões de salvar/cancelar
  - Interface consistente com outras views admin
- **Impacto**: Impossível editar categorias via interface web

### 12. Branding Incorreto no Admin Panel
- **Status**: 🔴 Pendente
- **Prioridade**: Média
- **Descrição**: Admin panel mostra "Laravel Sistema ERP" ao invés de "lumisERP"
- **Problema identificado**:
  - Header do painel admin usa `config('app.name')` que retorna "Laravel"
  - Deveria exibir "lumisERP" como nome da aplicação
  - Inconsistência de branding em todo o sistema
- **Arquivos relacionados**:
  - `resources/views/layouts/admin.blade.php` (header superior)
  - `config/app.php` (configuração do nome da app)
  - Possivelmente outros layouts que usam `config('app.name')`
- **Solução necessária**:
  - Alterar `APP_NAME=Laravel` para `APP_NAME=lumisERP` no `.env`
  - Ou criar configuração específica de branding
  - Verificar consistência em todos os layouts
- **Impacto**: Branding inconsistente e nome incorreto da aplicação

## 🔧 Issues de Funcionalidade (QA Findings)

### 13. Controllers Vazios - Leads e Purchases
- **Status**: 🔴 Pendente
- **Prioridade**: Alta
- **Descrição**: Controllers de Leads e Purchases existem mas estão completamente vazios
- **Problema identificado**:
  - `app/Http/Controllers/Admin/LeadController.php` - todos os métodos vazios
  - `app/Http/Controllers/Admin/PurchaseController.php` - todos os métodos vazios
  - Rotas existem mas retornam páginas em branco ou erro 500
- **Impacto técnico**:
  - Métodos `index()`, `create()`, `store()`, `show()`, `edit()`, `update()`, `destroy()` não implementados
  - Usuários podem acessar URLs mas não conseguem usar as funcionalidades
- **URLs afetadas**:
  - `/admin/leads` - página em branco
  - `/admin/purchases` - página em branco
- **Solução necessária**:
  - Implementar lógica de negócio nos controllers
  - Criar views correspondentes
  - Adicionar validações e tratamento de erros
- **Data identificada**: 2025-05-25

### 14. Views de Fornecedores Completamente Ausentes
- **Status**: 🔴 Pendente
- **Prioridade**: Alta
- **Descrição**: Diretório `resources/views/admin/suppliers/` não existe
- **Problema identificado**:
  - Controller `SupplierController.php` existe e tem implementação
  - Rotas estão configuradas corretamente
  - Mas nenhuma view foi criada para suppliers
  - Menu do admin aponta para suppliers mas resulta em erro
- **Erro esperado**: `View [admin.suppliers.index] not found`
- **Arquivos faltantes**:
  - `resources/views/admin/suppliers/index.blade.php`
  - `resources/views/admin/suppliers/create.blade.php`
  - `resources/views/admin/suppliers/edit.blade.php`
  - `resources/views/admin/suppliers/show.blade.php`
- **Impacto**: Impossível gerenciar fornecedores via interface web
- **Data identificada**: 2025-05-25

### 15. Views de Leads e Purchases Ausentes
- **Status**: 🔴 Pendente
- **Prioridade**: Alta
- **Descrição**: Diretórios de views para Leads e Purchases não existem
- **Problema identificado**:
  - Diretório `resources/views/admin/leads/` não existe
  - Diretório `resources/views/admin/purchases/` não existe
  - Menu do admin tem links para essas seções
  - Controllers existem mas views estão faltando
- **Arquivos faltantes**:
  - `resources/views/admin/leads/index.blade.php`
  - `resources/views/admin/leads/create.blade.php`
  - `resources/views/admin/leads/edit.blade.php`
  - `resources/views/admin/purchases/index.blade.php`
  - `resources/views/admin/purchases/create.blade.php`
  - `resources/views/admin/purchases/edit.blade.php`
- **Impacto**: Links no menu resultam em erro 500 ou páginas em branco
- **Data identificada**: 2025-05-25

### 16. Sistema de Estoque Inexistente
- **Status**: 🔴 Pendente
- **Prioridade**: Crítica
- **Descrição**: Não existe interface para controle de estoque no sistema
- **Problema identificado**:
  - Produtos têm campo `stock_quantity` no banco
  - PDV verifica estoque antes de vender
  - Mas não há interface para gerenciar/atualizar estoque
  - Não há controller `InventoryController`
  - Não há views de estoque
  - Não há menu para estoque
- **Impacto crítico**:
  - Impossível atualizar estoque quando chegam produtos
  - Impossível fazer inventário
  - Impossível corrigir divergências de estoque
  - Sistema de vendas pode falhar por falta de estoque
- **Arquivos necessários**:
  - `app/Http/Controllers/Admin/InventoryController.php`
  - `resources/views/admin/inventory/`
  - Rotas para estoque
  - Item no menu admin
- **Data identificada**: 2025-05-25

## 🚨 Issues de Segurança e Performance

### 17. Falta de Validação de Input em Controllers
- **Status**: 🔴 Pendente
- **Prioridade**: Alta
- **Descrição**: Controllers não implementam validação adequada de dados
- **Problema identificado**:
  - Controllers vazios não têm validação
  - Métodos `store()` e `update()` sem Request validation
  - Possível vulnerabilidade a ataques de injeção
- **Arquivos afetados**:
  - `app/Http/Controllers/Admin/LeadController.php`
  - `app/Http/Controllers/Admin/PurchaseController.php`
  - Possivelmente outros controllers
- **Solução necessária**:
  - Criar Form Request classes
  - Implementar validação de dados
  - Sanitização de inputs
- **Data identificada**: 2025-05-25

### 18. JavaScript Inline em Views - Problemas de CSP
- **Status**: 🔴 Pendente
- **Prioridade**: Média
- **Descrição**: Views contêm JavaScript inline que pode violar Content Security Policy
- **Problema identificado**:
  - `resources/views/admin/pos/index.blade.php` tem muito JavaScript inline
  - Pode causar problemas de segurança
  - Dificulta manutenção do código
  - Viola boas práticas de separação de responsabilidades
- **Solução sugerida**:
  - Mover JavaScript para arquivos separados
  - Usar event listeners ao invés de onclick inline
  - Implementar CSP headers
- **Data identificada**: 2025-05-25

## 🔍 Issues de UX/UI

### 19. Falta de Feedback Visual em Operações Assíncronas
- **Status**: 🔴 Pendente
- **Prioridade**: Média
- **Descrição**: Algumas operações não fornecem feedback adequado ao usuário
- **Problema identificado**:
  - Formulários podem ser submetidos múltiplas vezes
  - Falta de loading states em algumas operações
  - Usuário não sabe se ação foi executada com sucesso
- **Impacto UX**:
  - Confusão do usuário
  - Possível duplicação de dados
  - Experiência frustrante
- **Data identificada**: 2025-05-25

### 20. Inconsistência de Layout entre Seções
- **Status**: 🔴 Pendente
- **Prioridade**: Baixa
- **Descrição**: Diferentes seções do admin usam layouts ligeiramente diferentes
- **Problema identificado**:
  - Algumas páginas têm estilos inconsistentes
  - Botões com tamanhos diferentes
  - Espaçamentos variados
- **Impacto**: Experiência visual inconsistente
- **Data identificada**: 2025-05-25

## 📊 Resumo por Prioridade

### 🔥 Alta Prioridade (Crítico)
1. 🔴 **CRÍTICO**: Falta de Middleware Admin (#21)
2. 🔴 Sistema de Estoque Inexistente (#16)
3. 🔴 Possível Inconsistência Estoque/Vendas (#25)
4. 🔴 View de Edição de Categorias (#11)
5. 🔴 Controllers Vazios - Leads e Purchases (#13)
6. 🔴 Views de Fornecedores Ausentes (#14)
7. 🔴 Views de Leads e Purchases Ausentes (#15)
8. 🔴 Falta de Validação de Input (#17)
9. 🔴 Falta de Logs de Auditoria (#28)
10. 🔴 Página de Valores a Receber

### ⚡ Média Prioridade
11. 🔴 Agendamento de Entrega - Info Financeiras
12. 🔴 Branding Incorreto no Admin Panel (#12)
13. 🔴 Login Customer - Identidade Visual (#10)
14. 🔴 JavaScript Inline - Problemas CSP (#18)
15. 🔴 Performance JavaScript Inline (#22)
16. 🔴 Falta de Feedback Visual (#19)
17. 🔴 Tratamento de Erros Personalizado (#23)
18. 🔴 Falta de Busca Global Admin (#26)
19. 🔴 Dashboard com Métricas (#27)

### 🟡 Baixa Prioridade
20. 🔴 Inconsistência de Layout (#20)
21. 🔴 Falta de Seeders (#24)

## 🎯 Próximos Passos Atualizados

**Sugestão de ordem de correção (baseada em QA findings):**
1. 🔴 **URGENTE**: Criar sistema de controle de estoque (#16)
2. 🔴 **URGENTE**: Criar view de edição de categorias (#11)
3. 🔴 Implementar controllers de Leads e Purchases (#13)
4. 🔴 Criar todas as views faltantes (#14, #15)
5. 🔴 Implementar validação de dados (#17)
6. 🔴 Corrigir branding inconsistente (#12)
7. 🔴 Melhorar UX com feedback visual (#19)

---

**Última atualização**: 25/05/2025 (QA Analysis Completa)
**Total de issues**: 28
**Issues críticas**: 13
**Issues identificadas por QA**: 16
**Issues de segurança**: 3 (#17, #21, #28)
**Issues de performance**: 2 (#18, #22)
**Issues de UX**: 6 (#10, #19, #20, #23, #26, #27)

## 🔧 Issues de Configuração e Infraestrutura

### 21. Falta de Middleware de Autenticação Admin
- **Status**: 🔴 Pendente
- **Prioridade**: Crítica
- **Descrição**: Rotas admin podem não ter proteção adequada de autenticação
- **Problema identificado**:
  - Existe middleware `CustomerAuth` para clientes
  - Mas não há middleware específico para admin
  - Rotas admin podem estar desprotegidas
  - Possível acesso não autorizado ao painel admin
- **Impacto de segurança**:
  - Usuários não autenticados podem acessar admin
  - Dados sensíveis expostos
  - Operações críticas sem proteção
- **Arquivos relacionados**:
  - `app/Http/Middleware/` (falta AdminAuth.php)
  - `routes/web.php` (proteção de rotas)
- **Solução necessária**:
  - Criar middleware AdminAuth
  - Aplicar middleware em todas as rotas admin
  - Verificar autenticação e autorização
- **Data identificada**: 2025-05-25

### 22. Possível Problema de Performance com JavaScript Inline
- **Status**: 🔴 Pendente
- **Prioridade**: Média
- **Descrição**: JavaScript inline pode causar problemas de performance e cache
- **Problema identificado**:
  - Views com muito JavaScript inline (especialmente POS)
  - JavaScript não pode ser cacheado pelo browser
  - Código duplicado em múltiplas páginas
  - Dificulta minificação e otimização
- **Impacto técnico**:
  - Páginas mais lentas para carregar
  - Maior uso de banda
  - Pior experiência do usuário
- **Arquivos afetados**:
  - `resources/views/admin/pos/index.blade.php`
  - Possivelmente outras views admin
- **Solução sugerida**:
  - Extrair JavaScript para arquivos .js separados
  - Usar Vite para bundling e minificação
  - Implementar cache de assets
- **Data identificada**: 2025-05-25

### 23. Falta de Tratamento de Erros 404/500 Personalizado
- **Status**: 🔴 Pendente
- **Prioridade**: Média
- **Descrição**: Sistema não tem páginas de erro personalizadas
- **Problema identificado**:
  - Views faltantes resultam em erro 500 genérico
  - Não há página 404 personalizada
  - Usuários veem stack traces em produção
  - Experiência ruim quando algo dá errado
- **Impacto UX**:
  - Confusão do usuário
  - Aparência não profissional
  - Possível exposição de informações sensíveis
- **Arquivos necessários**:
  - `resources/views/errors/404.blade.php`
  - `resources/views/errors/500.blade.php`
  - `resources/views/errors/403.blade.php`
- **Data identificada**: 2025-05-25

## 🔍 Issues de Dados e Integridade

### 24. Falta de Seeders para Dados de Teste
- **Status**: 🔴 Pendente
- **Prioridade**: Baixa
- **Descrição**: Sistema não tem seeders adequados para popular dados de teste
- **Problema identificado**:
  - Dificulta desenvolvimento e testes
  - Novos desenvolvedores não conseguem testar facilmente
  - Ambiente de desenvolvimento vazio
- **Impacto desenvolvimento**:
  - Tempo perdido criando dados manualmente
  - Testes inconsistentes
  - Dificuldade para demonstrar funcionalidades
- **Solução necessária**:
  - Criar seeders para todas as entidades
  - Dados realistas para demonstração
  - Comando para popular ambiente de desenvolvimento
- **Data identificada**: 2025-05-25

### 25. Possível Inconsistência de Dados entre Estoque e Vendas
- **Status**: 🔴 Pendente
- **Prioridade**: Alta
- **Descrição**: Sem interface de estoque, pode haver inconsistências nos dados
- **Problema identificado**:
  - PDV verifica estoque mas não há como atualizar
  - Vendas podem reduzir estoque mas não há reposição
  - Possível estoque negativo
  - Dados inconsistentes entre tabelas
- **Impacto crítico**:
  - Vendas de produtos sem estoque
  - Relatórios incorretos
  - Problemas de fulfillment
- **Tabelas afetadas**:
  - `products.stock_quantity`
  - `order_items`
  - Possível necessidade de tabela `stock_movements`
- **Data identificada**: 2025-05-25

## 🎯 Issues de Usabilidade Específicas

### 26. Falta de Busca Global no Admin
- **Status**: 🔴 Pendente
- **Prioridade**: Média
- **Descrição**: Admin não tem funcionalidade de busca global
- **Problema identificado**:
  - Usuários precisam navegar por seções específicas
  - Não há busca unificada por produtos, clientes, pedidos
  - Experiência menos eficiente
- **Impacto UX**:
  - Tempo perdido navegando
  - Dificuldade para encontrar informações
  - Workflow menos eficiente
- **Solução sugerida**:
  - Implementar busca global no header admin
  - Buscar em múltiplas entidades
  - Resultados categorizados
- **Data identificada**: 2025-05-25

### 27. Falta de Dashboard com Métricas Importantes
- **Status**: 🔴 Pendente
- **Prioridade**: Média
- **Descrição**: Dashboard admin pode não ter métricas suficientes
- **Problema identificado**:
  - Falta visão geral do negócio
  - Métricas importantes podem estar faltando
  - Dificulta tomada de decisões
- **Métricas importantes faltantes**:
  - Vendas do dia/mês
  - Produtos com estoque baixo
  - Pedidos pendentes
  - Clientes ativos
  - Receita total
- **Data identificada**: 2025-05-25

### 28. Falta de Logs de Auditoria
- **Status**: 🔴 Pendente
- **Prioridade**: Alta
- **Descrição**: Sistema não registra ações importantes dos usuários
- **Problema identificado**:
  - Não há rastreamento de alterações
  - Impossível auditar ações
  - Dificulta debugging de problemas
  - Falta de accountability
- **Ações que deveriam ser logadas**:
  - Login/logout de usuários
  - Criação/edição/exclusão de produtos
  - Vendas realizadas
  - Alterações de estoque
  - Alterações de preços
- **Solução necessária**:
  - Implementar sistema de audit logs
  - Usar Laravel Activity Log ou similar
  - Interface para visualizar logs
- **Data identificada**: 2025-05-25

### 29. PDV - Problema de Autenticação/JavaScript
- **Status**: 🔧 Em Correção
- **Prioridade**: Crítica
- **Descrição**: PDV não funciona devido a problemas de autenticação ou execução JavaScript
- **Problema identificado**:
  - Busca de produtos e clientes não funciona no browser
  - Endpoint retorna "Unauthenticated" quando testado via curl
  - JavaScript implementado corretamente mas não executa
  - Possível problema de sessão ou CSRF token
- **Sintomas reportados pelo usuário**:
  - "Ao digitar nada acontece" na busca de produtos
  - Busca de clientes também não funciona
  - Interface carrega mas funcionalidades AJAX falham
- **Investigação técnica necessária**:
  - Verificar se usuário está autenticado corretamente
  - Verificar console do browser para erros JavaScript
  - Verificar se CSRF token está sendo enviado nas requisições
  - Verificar se elementos DOM estão sendo encontrados
  - Verificar se event listeners estão sendo anexados
  - Verificar se debounce function está funcionando
- **Possíveis causas**:
  - Sessão expirada ou inválida
  - CSRF token inválido ou ausente
  - Erro JavaScript silencioso
  - Problema de middleware de autenticação
  - Problema de CORS ou headers
- **Arquivos relacionados**:
  - `resources/views/admin/pos/index.blade.php` (JavaScript)
  - `app/Http/Controllers/Admin/POSController.php` (endpoints)
  - `routes/web.php` (middleware auth)
- **Correções implementadas**:
  - ✅ Adicionada seção de debug no topo da página PDV
  - ✅ Criado endpoint `/admin/pos/debug` para verificar autenticação
  - ✅ Adicionados logs detalhados no JavaScript
  - ✅ Criados botões de teste para endpoints
  - ✅ Verificação automática de elementos DOM
  - ✅ Teste automático de funcionalidade na inicialização
  - ✅ Alertas visuais para erros de inicialização
- **Como usar as ferramentas de debug**:
  1. Acesse `/admin/pos` logado como admin
  2. Verifique a seção amarela de debug no topo
  3. Clique nos botões "Test Search", "Test Auth", "Debug Info"
  4. Abra o console do browser (F12) para ver logs detalhados
  5. Verifique se há erros JavaScript ou de autenticação
- **Data identificada**: 2025-05-25 

### 30. PDV - JavaScript Functions Not in Global Scope
- **Status**: ✅ Resolvido
- **Prioridade**: Crítica
- **Descrição**: Funções JavaScript testAuthEndpoint e testDebugEndpoint não estavam no escopo global
- **Problema identificado**:
  - Funções definidas dentro de escopo local não eram acessíveis pelos botões onclick
  - Causava erros "ReferenceError: testAuthEndpoint is not defined"
- **Correção aplicada**:
  - ✅ Movidas funções para window.testAuthEndpoint e window.testDebugEndpoint
  - ✅ Funções agora acessíveis globalmente
  - ✅ Botões de debug funcionando corretamente
  - ✅ Corrigidos botões onclick para usar window.functionName()
  - ✅ Melhorada função testSearchEndpoint para exibir produtos encontrados
  - ✅ Adicionado teste automático ao carregar página
- **Data**: 2025-05-25

### 31. Purchase Model - Inconsistência de Campos nas Views
- **Status**: ✅ Resolvido
- **Prioridade**: Alta
- **Descrição**: Views de Purchase usando nomes de campos incorretos
- **Problema identificado**:
  - View show.blade.php usando `reference_no` ao invés de `purchase_number`
  - View usando `purchase_date` ao invés de `delivery_date`
  - View usando `total_amount` ao invés de `total`
- **Correção aplicada**:
  - ✅ Corrigidos nomes dos campos na view show.blade.php
  - ✅ Alinhamento com o modelo Purchase e controller
- **Data**: 2025-05-25

## 🆕 Novas Funcionalidades Solicitadas

### 32. Sistema de Opcionais de Produtos
- **Status**: 🔴 Pendente
- **Prioridade**: Alta
- **Descrição**: Produtos devem ter opcionais como cor, tamanho, material, etc.
- **Requisitos funcionais**:
  - Produtos podem ter múltiplos tipos de opcionais (cor, tamanho, material, acabamento, etc.)
  - Cada opcional pode ter múltiplas opções (ex: cores: azul, vermelho, verde)
  - Opcionais podem ser obrigatórios ou facultativos
  - Interface para gerenciar opcionais no admin
  - Seleção de opcionais no PDV e e-commerce
- **Impacto técnico**:
  - Nova tabela `product_options` (tipo do opcional: cor, tamanho, etc.)
  - Nova tabela `product_option_values` (valores: azul, vermelho, P, M, G, etc.)
  - Nova tabela `product_variants` (combinações específicas de opcionais)
  - Relacionamento many-to-many entre produtos e opcionais
- **Arquivos a criar/modificar**:
  - Migration para tabelas de opcionais
  - Models: ProductOption, ProductOptionValue, ProductVariant
  - Views para gerenciar opcionais no admin
  - Interface de seleção no PDV e e-commerce
- **Data identificada**: 2025-05-25

### 33. Sistema de Preços Diferenciados por Opcionais
- **Status**: 🔴 Pendente
- **Prioridade**: Alta
- **Descrição**: Alguns opcionais devem ter preços diferentes (ex: cor especial +R$10)
- **Requisitos funcionais**:
  - Opcionais podem ter preço adicional ou desconto
  - Preço pode ser valor fixo (+R$10) ou percentual (+15%)
  - Cálculo automático do preço final baseado nos opcionais selecionados
  - Exibição clara do preço base + adicionais
- **Impacto técnico**:
  - Campo `price_modifier` na tabela `product_option_values`
  - Campo `price_type` (fixed, percentage) na tabela `product_option_values`
  - Lógica de cálculo de preço no frontend e backend
  - Atualização em tempo real do preço no PDV e e-commerce
- **Exemplo de uso**:
  - Camiseta básica: R$ 50,00
  - Cor especial (dourado): +R$ 15,00
  - Tamanho GG: +R$ 5,00
  - Preço final: R$ 70,00
- **Data identificada**: 2025-05-25

### 34. Sistema de Kits de Produtos
- **Status**: 🔴 Pendente
- **Prioridade**: Alta
- **Descrição**: Deve ser possível criar kits com múltiplos produtos
- **Requisitos funcionais**:
  - Kit é um produto especial que contém outros produtos
  - Cada item do kit pode ter quantidade específica
  - Preço do kit pode ser diferente da soma dos produtos individuais
  - Controle de estoque baseado nos produtos componentes
  - Venda de kit reduz estoque dos produtos individuais
- **Impacto técnico**:
  - Nova tabela `product_kits` (relaciona produto kit com produtos componentes)
  - Campo `is_kit` na tabela `products`
  - Lógica especial para controle de estoque de kits
  - Interface para montar kits no admin
  - Exibição de componentes do kit no PDV e e-commerce
- **Exemplo de uso**:
  - Kit "Conjunto Completo": Camiseta + Calça + Boné
  - Quantidade: 1 + 1 + 1
  - Preço individual: R$ 50 + R$ 80 + R$ 30 = R$ 160
  - Preço do kit: R$ 140 (desconto de R$ 20)
- **Arquivos a criar/modificar**:
  - Migration para tabela product_kits
  - Model ProductKit
  - Controller para gerenciar kits
  - Views para criar/editar kits
  - Lógica de estoque para kits
- **Data identificada**: 2025-05-25

### 35. Interface de Seleção de Opcionais no PDV
- **Status**: 🔴 Pendente
- **Prioridade**: Alta
- **Descrição**: PDV deve permitir seleção de opcionais ao adicionar produto
- **Requisitos funcionais**:
  - Modal ou sidebar para seleção de opcionais
  - Exibição visual das opções (cores com preview, tamanhos, etc.)
  - Atualização em tempo real do preço conforme seleção
  - Validação de opcionais obrigatórios
  - Adição ao carrinho com opcionais selecionados
- **Impacto UX**:
  - Interface intuitiva para seleção rápida
  - Preview visual das opções quando possível
  - Indicação clara de preços adicionais
  - Fluxo otimizado para vendas rápidas
- **Arquivos a modificar**:
  - `resources/views/admin/pos/index.blade.php`
  - JavaScript do PDV para modal de opcionais
  - CSS para interface visual atrativa
- **Data identificada**: 2025-05-25

### 36. Interface de Seleção de Opcionais no E-commerce
- **Status**: 🔴 Pendente
- **Prioridade**: Média
- **Descrição**: E-commerce deve permitir seleção de opcionais na página do produto
- **Requisitos funcionais**:
  - Seletores visuais para cada tipo de opcional
  - Cores com preview visual (swatches)
  - Tamanhos com guia de medidas
  - Atualização de preço e disponibilidade em tempo real
  - Validação antes de adicionar ao carrinho
- **Impacto UX**:
  - Experiência de compra mais rica
  - Redução de dúvidas do cliente
  - Aumento na conversão de vendas
- **Arquivos a modificar**:
  - Views do e-commerce para página de produto
  - JavaScript para interatividade
  - CSS para interface visual
- **Data identificada**: 2025-05-25

### 37. Controle de Estoque por Variantes
- **Status**: 🔴 Pendente
- **Prioridade**: Crítica
- **Descrição**: Estoque deve ser controlado por combinação de opcionais (variantes)
- **Requisitos funcionais**:
  - Cada combinação de opcionais tem estoque próprio
  - Ex: Camiseta Azul P = 10 unidades, Camiseta Azul M = 5 unidades
  - Interface para gerenciar estoque por variante
  - Verificação de disponibilidade antes da venda
  - Relatórios de estoque por variante
- **Impacto técnico**:
  - Tabela `product_variant_stock` para controle granular
  - Lógica complexa de verificação de estoque
  - Interface administrativa para gestão
  - Integração com PDV e e-commerce
- **Exemplo de uso**:
  - Produto: Camiseta
  - Variante 1: Azul + P = 10 unidades
  - Variante 2: Azul + M = 5 unidades
  - Variante 3: Vermelho + P = 0 unidades (indisponível)
- **Data identificada**: 2025-05-25

### 38. PDV - Produtos Não Aparecem na Busca
- **Status**: ✅ Resolvido
- **Prioridade**: Crítica
- **Descrição**: Usuário não consegue ver produtos no PDV ao digitar na busca
- **Problema identificado**:
  - Layout admin não tinha `@stack('scripts')` no final
  - JavaScript em `@push('scripts')` não estava sendo renderizado
  - Busca de produtos não funcionava por falta de JavaScript
- **Sintomas reportados**:
  - "não consigo ver produtos no pdv"
  - "on fill //*[@id="product-search"] with caneca nothing is trigged"
  - "no backend requests are made"
- **Investigação técnica**:
  - ✅ Verificado: 9 produtos ativos no banco
  - ✅ Verificado: Todos têm estoque > 0
  - ✅ Verificado: Controller POSController existe e está implementado
  - ✅ Verificado: Rota admin.pos.search-products existe
  - ✅ Verificado: Modelo Product tem scope active()
  - ✅ Identificado: Falta de `@stack('scripts')` no layout
- **Correção aplicada**:
  - ✅ Adicionado `@stack('scripts')` no final do layout admin.blade.php
  - ✅ JavaScript agora carrega corretamente
  - ✅ Busca de produtos funcionando perfeitamente
  - ✅ Produtos aparecem na interface
  - ✅ Carrinho funciona corretamente
  - ✅ Botão "Finalizar Venda" habilita quando há produtos
- **Teste realizado**:
  - ✅ Busca por "caneca" retorna 4 produtos
  - ✅ Produtos são exibidos com preço e estoque
  - ✅ Clique no produto adiciona ao carrinho
  - ✅ Contador de itens atualiza corretamente
  - ✅ Interface totalmente funcional
- **Data resolvida**: 2025-05-25

## 📊 Resumo Atualizado por Prioridade

### 🔥 Alta Prioridade (Crítico)
1. ✅ **RESOLVIDO**: PDV JavaScript Functions (#30)
2. ✅ **RESOLVIDO**: Purchase Model Fields (#31)
3. ✅ **RESOLVIDO**: PDV - Produtos Não Aparecem na Busca (#38)
4. 🔴 Sistema de Opcionais de Produtos (#32)
5. 🔴 Sistema de Preços Diferenciados (#33)
6. 🔴 Sistema de Kits de Produtos (#34)
7. 🔴 Interface PDV para Opcionais (#35)
8. 🔴 Controle de Estoque por Variantes (#37)
9. 🔴 Sistema de Estoque Inexistente (#16)
10. 🔴 View de Edição de Categorias (#11)

### ⚡ Média Prioridade
11. 🔴 Interface E-commerce para Opcionais (#36)
12. 🔴 Branding Incorreto no Admin Panel (#12)
13. 🔴 Login Customer - Identidade Visual (#10)
14. 🔴 JavaScript Inline - Problemas CSP (#18)

---

**Última atualização**: 25/05/2025 (PDV totalmente funcional)
**Total de issues**: 38
**Issues críticas**: 15
**Issues resolvidas**: 3
**Novas funcionalidades**: 6 (opcionais, preços, kits, interfaces)
**Foco atual**: Sistema de opcionais e variantes de produtos
**Última correção**: PDV - Busca de produtos funcionando perfeitamente 