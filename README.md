<div align="center">

# 🚀 Exercício Técnico Interprocess

[![PHP](https://img.shields.io/badge/PHP-8.5.7-777BB4?logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-13.8-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Node.js](https://img.shields.io/badge/Node.js-24-339933?logo=node.js&logoColor=white)](https://nodejs.org)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.5.38-4FC08D?logo=vue.js&logoColor=white)](https://vuejs.org)
[![Vite](https://img.shields.io/badge/Vite-8.0.16-646CFF?logo=vite&logoColor=white)](https://vitejs.dev)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-18.4-4169E1?logo=postgresql&logoColor=white)](https://www.postgresql.org)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker&logoColor=white)](https://www.docker.com)

**Solução full-stack desenvolvida para o processo seletivo da [Interprocess](https://www.interprocess.com.br).**

</div>

---

## 📋 Visão Geral

Aplicação completa com **Laravel** no backend, **Vue.js 3 + Vite** no frontend e **PostgreSQL 18.4** como banco de dados, totalmente containerizada com **Docker**.

O frontend utiliza o tema [Volt Bootstrap 5 Dashboard](https://github.com/themesberg/volt-bootstrap-5-dashboard) como base de layout, oferecendo uma interface moderna, responsiva e profissional.

---

## 🛠️ Stack Tecnológica

| Camada | Tecnologia | Versão |
|--------|------------|--------|
| Backend | Laravel | 13.8 |
| Frontend | Vue.js | 3.5.38 |
| Banco de dados | PostgreSQL | 18.4 |
| Containerização | Docker + Docker Compose | latest |

---

## 📚 Principais Bibliotecas

### ⚙️ Backend (Laravel)

- **[geekcom/validator-docs](https://github.com/geekcom/validator-docs)** — validação de documentos brasileiros (CPF, CNPJ, RG etc.).
- **[FakerPHP](https://fakerphp.github.io/)** — geração de dados fictícios para seeds e testes.

### 🎨 Frontend (Vue.js 3)

- **[Vue Router](https://router.vuejs.org/)** — roteamento SPA.
- **[Pinia](https://pinia.vuejs.org/)** — gerenciamento de estado.
- **[Bootstrap](https://getbootstrap.com/)** — estilização e componentes responsivos.
- **[SweetAlert2](https://sweetalert2.github.io/)** — modais e alertas elegantes.
- **[vue-imask](https://github.com/uNmAnNiR/imaskjs/tree/master/packages/vue-imask)** — máscaras de entrada.
- **[@fnando/cpf](https://github.com/fnando/cpf)** — validação de CPF.
- **[tom-select](https://tom-select.js.org/)** — selects avançados.

---

## ✅ Pré-requisitos

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- [Git](https://git-scm.com/) — necessário apenas para clonar o repositório localmente.

> Não é necessário instalar PHP, Node.js, Composer ou PostgreSQL localmente. Todo o ambiente roda dentro dos containers.

---

## ▶️ Como executar

### 1. Clone o repositório

```bash
git clone <url-do-repositorio>
cd exercicioTecnicoInterprocess
```

### 2. Suba os containers

```bash
docker compose up -d
```

### 3. Instale e configure o backend

```bash
docker exec -it php856_workspace_interprocess bash -c "cd /var/www/backend && composer install && cp .env.example .env && php artisan key:generate && php artisan migrate && php artisan db:seed"
```

> O `.env.example` já está configurado para conectar ao PostgreSQL definido no `docker-compose.yml`, então o banco de dados é preparado automaticamente.

### 4. Instale as dependências do frontend

```bash
docker exec -it php856_workspace_interprocess bash -c "cd /var/www/frontend && npm install"
```

### 5. Inicie backend e frontend

Com o `concurrently` instalado no container, execute ambos em um único terminal:

```bash
docker exec -it php856_workspace_interprocess bash -c "concurrently --names backend,frontend --prefix-colors blue,green 'cd /var/www/backend && php artisan serve --host=0.0.0.0 --port=8000' 'cd /var/www/frontend && npm run dev -- --host'"
```

---

## 🌐 Acessando a aplicação

| Serviço | URL |
|---------|-----|
| Frontend | [http://localhost:5173](http://localhost:5173) |
| Backend | [http://localhost:8000](http://localhost:8000) |
| PostgreSQL | `localhost:5432` |

---

## ⌨️ Comandos úteis

```bash
# Acessar o container de trabalho
docker exec -it php856_workspace_interprocess bash

# Rodar testes do backend
docker exec -it php856_workspace_interprocess bash -c "cd /var/www/backend && php artisan test"

# Reformatar o código PHP com o Laravel Pint
docker exec -it php856_workspace_interprocess bash -c "cd /var/www/backend && ./vendor/bin/pint"

# Reiniciar os containers
docker compose restart

# Parar todos os serviços
docker compose down

# Recriar os containers do zero
docker compose up -d --build
```

---

## ⭐ Destaques da solução

- Ambiente 100% containerizado, garantindo consistência entre máquinas de desenvolvimento.
- Separação clara entre backend e frontend, facilitando manutenção e evolução.
- Validações de dados brasileiros integradas ao backend.
- Interface responsiva baseada em Bootstrap 5.

---

<div align="center">

Desenvolvido com atenção aos detalhes para o processo seletivo da **Interprocess**.

</div>
