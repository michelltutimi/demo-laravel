# Laravel 11 Showcase & Relational Feed Engine (PoC)

A full-stack demonstration application engineered to showcase modern backend architecture, containerized deployment, and clean Laravel conventions.

---

## 🚀 Architectural & Engineering Highlights

- **Relational Feed Engine:** Queries posts from followed users with an automated fallback to global trending feed when user interactions are low.
- **API Payload Sanitization & PII Protection:** Explicit `$hidden` model attribute configuration ensuring sensitive user data (e.g., emails) is excluded from public API responses.
- **Production Cloud Infrastructure:** Containerized architecture deployed on an AWS EC2 instance connected to an isolated AWS RDS MariaDB instance.
- **Server-Level Upload Optimization:** Server runtime and reverse proxy configuration customized to handle large media uploads cleanly.
- **Reactive UI Layer:** Asynchronous infinite scrolling and dynamic interaction handling using Alpine.js and Bootstrap 5.
- **Reverse Proxy Compatibility:** Configured to trust Nginx proxy headers (`TrustProxies`) for secure SSL/TLS terminating reverse proxies.

---

## 🛠️ Tech Stack

- **Backend:** Laravel 11 (PHP 8.2 Apache)
- **Frontend:** Alpine.js, Bootstrap 5, SVG Vector Icons
- **Database:** MariaDB 11.4 (Local Docker container / AWS RDS in Production)
- **Orchestration & Infrastructure:** Docker, Nginx, AWS EC2, AWS RDS

---

## 🗺️ Engineering Roadmap (Phase 2)

- [ ] Automated image content moderation via **AWS Rekognition**.
- [ ] Full-text search and high-speed indexing using **AWS OpenSearch**.
- [ ] Event-driven background image processing with **AWS Lambda**.

---

## 🎯 Local Development & Deployment Setup

Follow these step-by-step instructions to configure and run the application locally or in a staging environment.

### 1. Shared Docker Network Setup
This setup relies on an external bridge network named `la_network`. Create it on your host system if it does not exist:

```bash
docker network create la_network
```

### 2. Environment Configuration
Clone the repository and copy `.env.example` to create your local `.env` file:

```bash
git clone https://github.com/michelltutimi/demo-laravel.git
cd demo-laravel
cp .env.example .env
```

*Note: Open `.env` and uncomment/configure the MariaDB connection variables (`DB_CONNECTION=mysql`, `DB_HOST=mariadb_01`, `DB_PORT=3306`, etc.) that are provided as commented reference lines in `.env.example` to match your database settings.*

### 3. Docker Compose Configuration
Copy the provided `docker-compose.example.yml` template from the root directory to create your active compose file:

```bash
cp docker-compose.example.yml docker-compose.yml
```

> **⚠️ Deployment Notice (Local vs. AWS RDS):**
> The `docker-compose.example.yml` file includes two services: `laravel_web` (PHP 8.2 Apache) and `mariadb_01` (MariaDB container).
> - **Local Development:** Keep both services active to run a local MariaDB container.
> - **Production / AWS EC2 with RDS:** Remove or comment out the `mariadb_01` service block in `docker-compose.yml` and set `DB_HOST` in your `.env` file to your AWS RDS endpoint. This avoids deploying an unnecessary local database container on your EC2 instance.

### 4. Launch Container Stack
Build and start the container stack in detached mode:

```bash
docker compose up -d --build
```

### 5. Application Initialization
Execute key generation, database migrations, and public storage symlinking inside the web container (`laravel_web`):

```bash
# Generate Laravel Application Key
docker compose exec laravel_web php artisan key:generate

# Execute Database Migrations
docker compose exec laravel_web php artisan migrate

# Link public storage directory for media uploads
docker compose exec laravel_web php artisan storage:link
```

Once running, the application will be accessible at `http://localhost:8082`.