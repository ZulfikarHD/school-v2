# School SaaS Platform

Platform manajemen sekolah berbasis SaaS — multi-tenant, modern, dan siap produksi.

## Tech Stack

- **Backend:** Laravel 12, PHP 8.3, PostgreSQL 16, Redis 7
- **Frontend:** Vue 3, TypeScript, Inertia.js v2 (SSR), Tailwind CSS 4
- **Search:** Meilisearch
- **Storage:** MinIO (S3-compatible)
- **Queue:** Laravel Horizon + Redis
- **Package Manager:** Yarn

## Prerequisites

- [Docker](https://docs.docker.com/get-docker/) & [Docker Compose](https://docs.docker.com/compose/install/) v2+
- Git

## Quick Start

### 1. Clone & configure environment

```bash
git clone <repository-url> school-v2
cd school-v2
cp .env.example .env
```

Edit `.env` if needed — all forwarded ports are configurable:

| Variable                       | Default | Description             |
|--------------------------------|---------|-------------------------|
| `APP_PORT`                     | 80      | Nginx (application)     |
| `VITE_PORT`                    | 5173    | Vite dev server (HMR)   |
| `FORWARD_DB_PORT`              | 5432    | PostgreSQL              |
| `FORWARD_REDIS_PORT`           | 63790   | Redis                   |
| `FORWARD_MEILISEARCH_PORT`     | 7700    | Meilisearch             |
| `FORWARD_MINIO_PORT`           | 9000    | MinIO API               |
| `FORWARD_MINIO_CONSOLE_PORT`   | 9001    | MinIO Console           |
| `FORWARD_MAILPIT_PORT`         | 1025    | Mailpit SMTP            |
| `FORWARD_MAILPIT_DASHBOARD_PORT` | 8025  | Mailpit email viewer    |

### 2. Start all services

```bash
docker compose up -d
```

This starts: **app** (PHP-FPM), **nginx**, **postgres**, **redis**, **meilisearch**, **minio**, **vite** (HMR), **mailpit**, **scheduler**.

> **Note:** The **horizon** container will start once `laravel/horizon` is installed (US-S0.2).

### 3. Install dependencies & setup

```bash
# Install PHP dependencies
docker compose exec app composer install

# Generate app key (if not set)
docker compose exec app php artisan key:generate

# Run migrations
docker compose exec app php artisan migrate

# Install JS dependencies (handled automatically by vite container, but for manual use):
docker compose exec vite yarn install
```

### 4. Access the application

| Service           | URL                          |
|-------------------|------------------------------|
| Application       | http://localhost              |
| Vite HMR          | http://localhost:5173         |
| MinIO Console     | http://localhost:9001         |
| Meilisearch       | http://localhost:7700         |
| Mailpit           | http://localhost:8025         |
| PostgreSQL        | localhost:5432                |
| Redis             | localhost:6379                |

## Common Commands

```bash
# Start all services
docker compose up -d

# Stop all services
docker compose down

# View logs
docker compose logs -f app
docker compose logs -f vite

# Run artisan commands
docker compose exec app php artisan <command>

# Run tests
docker compose exec app php artisan test --compact

# Run Pint (code formatting)
docker compose exec app vendor/bin/pint --dirty --format agent

# Rebuild containers after Dockerfile changes
docker compose up -d --build

# Reset everything (removes volumes)
docker compose down -v
```

## Project Structure

```
school-v2/
├── app/                  # Laravel application code
├── bootstrap/            # Laravel bootstrap
├── config/               # Laravel configuration
├── database/             # Migrations, factories, seeders
├── docker/               # Docker configuration
│   ├── app/Dockerfile    # PHP 8.3 + extensions
│   ├── nginx/            # Nginx config
│   ├── scheduler/        # Laravel scheduler
│   └── horizon/          # Laravel Horizon
├── docs/                 # Documentation & scrum
├── public/               # Web entry point
├── resources/            # Views, JS, CSS
├── routes/               # Route definitions
├── storage/              # App storage
├── tests/                # PHPUnit tests
├── docker-compose.yml    # Base services
└── docker-compose.dev.yml # Dev overrides (HMR, volumes)
```

## Environment Notes

- **Hot Reload:** PHP changes reflect immediately (source mounted). Vue/Vite HMR works via the `vite` container on port 5173.
- **Database:** PostgreSQL 16 with persistent volume. Data survives `docker compose down` but not `docker compose down -v`.
- **Email:** Mailpit captures all outgoing email in development — view at http://localhost:8025.
- **Storage:** MinIO provides S3-compatible local storage — manage files at http://localhost:9001 (login: minioadmin/minioadmin).
