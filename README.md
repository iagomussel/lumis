# Lumis ERP - Sistema de Gestão Empresarial

Um sistema ERP completo desenvolvido em Laravel com interface administrativa moderna e responsiva.

## 🚀 Funcionalidades

### 📊 Dashboard Administrativo
- Estatísticas em tempo real
- Gráficos de vendas e receita
- Alertas de estoque baixo
- Pedidos e leads recentes

### 🏷️ Gestão de Produtos
- **Categorias**: Organização hierárquica (Insumos e Ativos)
- **Produtos**: Cadastro completo com:
  - SKU e código de barras
  - Preços de venda e custo
  - Controle de estoque (mín/máx)
  - Produtos personalizáveis
  - Upload de imagens e arquivos
  - Campos customizados
  - Dimensões e peso

### 👥 Gestão de Clientes
- Cadastro completo (PF/PJ)
- Endereço e dados de contato
- Limite de crédito
- Histórico de pedidos
- Status (ativo/inativo/bloqueado)

### 📋 Gestão de Pedidos
- Criação e acompanhamento de pedidos
- Status detalhado (pendente → entregue)
- Cálculo automático de totais
- Endereço de entrega
- Métodos de pagamento
- Geração automática de números

### 🏢 Gestão de Fornecedores
- Cadastro completo com CNPJ
- Avaliação e rating
- Condições comerciais
- Histórico de compras

### 🛒 Gestão de Compras/Cotações
- Cotações e pedidos de compra
- Controle de recebimento
- Condições de pagamento
- Acompanhamento de status

### 🎯 Gestão de Leads (CRM)
- Lead scoring
- Funil de vendas
- Origem dos leads
- Probabilidade de fechamento
- Acompanhamento de follow-ups

### 🎁 Sistema de Promoções
- Diferentes tipos de desconto
- Códigos promocionais
- Condições de aplicação
- Disparo de email marketing
- Controle de uso

### 📝 Histórico de Atividades
- Log completo de todas as ações
- Rastreabilidade de mudanças
- Auditoria do sistema

## 🛠️ Tecnologias Utilizadas

- **Backend**: Laravel 11
- **Frontend**: Blade Templates + Tailwind CSS
- **Banco de Dados**: SQLite (desenvolvimento) / MySQL (produção)
- **Autenticação**: Laravel Breeze
- **Permissões**: Spatie Laravel Permission
- **Upload de Imagens**: Intervention Image
- **Busca**: Laravel Scout

## 📋 Pré-requisitos

- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite ou MySQL

## 🚀 Instalação

1. **Clone o repositório**
```bash
git clone <repository-url>
cd lumis-erp
```

2. **Instale as dependências**
```bash
composer install
npm install
```

3. **Configure o ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure o banco de dados**
Edite o arquivo `.env` com suas configurações de banco:
```env
DB_CONNECTION=sqlite
# ou para MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=lumis_erp
# DB_USERNAME=root
# DB_PASSWORD=
```

5. **Execute as migrations e seeders**
```bash
php artisan migrate
php artisan db:seed
```

6. **Compile os assets**
```bash
npm run build
```

7. **Inicie o servidor**
```bash
php artisan serve
```

## 👤 Usuários de Teste

Após executar os seeders, você terá os seguintes usuários:

| Email | Senha | Perfil |
|-------|-------|--------|
| admin@lumiserp.com | password | Administrador |
| vendedor@lumiserp.com | password | Vendedor |
| comprador@lumiserp.com | password | Comprador |

## 🎯 Acesso ao Sistema

- **URL**: http://localhost:8000
- **Admin Dashboard**: http://localhost:8000/admin
- **Login**: http://localhost:8000/login

## 📱 Interface

O sistema possui uma interface moderna e responsiva com:

- **Sidebar** com navegação organizada por módulos
- **Cards de estatísticas** no dashboard
- **Tabelas responsivas** para listagens
- **Formulários intuitivos** para cadastros
- **Alertas visuais** para estoque baixo
- **Badges coloridos** para status

## 🔧 Estrutura do Projeto

```
app/
├── Http/Controllers/Admin/    # Controllers do admin
├── Models/                    # Models Eloquent
└── ...

database/
├── migrations/               # Migrations do banco
└── seeders/                 # Seeders com dados de exemplo

resources/
├── views/
│   ├── admin/               # Views do admin
│   └── layouts/             # Layouts base
└── ...

routes/
└── web.php                  # Rotas da aplicação
```

## 🎨 Personalização

### Cores e Tema
O sistema usa Tailwind CSS. Para personalizar cores, edite:
- `tailwind.config.js`
- `resources/css/app.css`

### Layout
O layout principal está em:
- `resources/views/layouts/admin.blade.php`

### Sidebar
Para adicionar novos itens na sidebar, edite o layout admin.

## 📊 Funcionalidades Avançadas

### Produtos Personalizáveis
- Campos customizados em JSON
- Upload múltiplo de imagens
- Anexos de arquivos

### Sistema de Permissões
- Baseado no Spatie Laravel Permission
- Roles e permissões granulares
- Controle de acesso por módulo

### Alertas Inteligentes
- Estoque baixo automático
- Leads sem follow-up
- Pedidos pendentes

### Relatórios
- Vendas por período
- Produtos mais vendidos
- Performance de leads
- Análise de fornecedores

## 🔒 Segurança

- Autenticação Laravel Breeze
- Proteção CSRF
- Validação de dados
- Sanitização de inputs
- Controle de acesso baseado em roles

## 📈 Performance

- Eager loading nos relacionamentos
- Índices otimizados no banco
- Cache de consultas frequentes
- Paginação eficiente

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo LICENSE para mais detalhes.

## 📞 Suporte

Para suporte e dúvidas:
- Email: suporte@lumiserp.com
- Issues: GitHub Issues

---

**Desenvolvido com ❤️ usando Laravel**
