# 🎫 Multi-Tenant Helpdesk SaaS Platform

A production-ready, enterprise-grade helpdesk ticketing system built with **Laravel 12**. Designed with clean architecture, SOLID principles, and scalable multi-tenant support.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![Sanctum](https://img.shields.io/badge/Auth-Sanctum-38bdf8?style=flat-square)
![Bootstrap](https://img.shields.io/badge/UI-Bootstrap%205-7952B3?style=flat-square&logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

---

## 📋 Table of Contents

- [Project Overview](#-project-overview)
- [Features](#-features)
- [Architecture](#-architecture)
- [Installation](#-installation)
- [Environment Setup](#-environment-setup)
- [Database Setup](#-database-setup)
- [Queue Setup](#-queue-setup)
- [Storage Link](#-storage-link)
- [API Documentation](#-api-documentation)
- [Postman Collection](#-postman-collection)
- [Screenshots](#-screenshots)
- [Contributing](#-contributing)
- [License](#-license)

---

## 🏗 Project Overview

This platform enables organizations to manage customer support tickets through a centralized, multi-tenant system. Each organization operates in an isolated data environment while sharing the same application instance.

### Key Highlights

- **Multi-Tenant Architecture** — Row-level isolation using `organization_id` with automatic global scopes
- **Role-Based Access Control (RBAC)** — Super Admin, Organization Admin, Support Agent, Customer
- **Enterprise Patterns** — Repository, Service Layer, Form Requests, Policies, API Resources
- **Queue-Driven** — Asynchronous email notifications for ticket events
- **Audit Trail** — Full change history for all critical models

---

## ✨ Features

| Module | Capabilities |
|--------|-------------|
| **Authentication** | Register, Login, Logout, Forgot/Reset Password (Sanctum) |
| **Organizations** | CRUD, Search, Filter, Sort, Pagination, Soft Deletes |
| **Users** | CRUD, Role Assignment, Permission Management |
| **Tickets** | Create, Update, Delete, Assign, Reassign, Close, Search, Filter, Sort |
| **Comments** | Add, Edit, Delete (linked to tickets) |
| **Attachments** | Upload, Download, Delete (Laravel Storage) |
| **Audit Logs** | Automatic tracking of Create, Update, Delete events |
| **Dashboard** | Statistics cards, charts (Bootstrap 5 + Chart.js) |
| **Email Notifications** | Queue-based emails for Ticket Created, Assigned, Closed |

---

## 🏛 Architecture

### Folder Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/              # API controllers (JSON responses)
│   │   ├── Admin/            # Admin dashboard controller
│   │   └── Web/              # Web controllers (Blade views)
│   ├── Middleware/            # Tenant, Role, Permission middleware
│   ├── Requests/             # Form Request validation classes
│   └── Resources/            # API Resource transformers
├── Interfaces/               # Repository contracts
├── Repositories/             # Repository implementations
├── Services/                 # Business logic layer
├── Models/                   # Eloquent models
├── Policies/                 # Authorization policies
├── Observers/                # Model observers (Audit Logs)
├── Events/                   # Domain events
├── Listeners/                # Event listeners
├── Jobs/                     # Queued jobs
├── Mail/                     # Mailable classes
├── Traits/                   # Reusable traits
├── Helpers/                  # Utility helpers
└── Providers/                # Service providers
```

### Repository Pattern

The Repository Pattern abstracts the data access layer, making the application more testable and maintainable.

```
Controller → Service → Repository → Model → Database
```

**Flow:**
1. **Controller** receives the HTTP request and delegates to the Service.
2. **Service** contains business logic, calls Repository methods.
3. **Repository** interacts with the Eloquent Model for data operations.
4. **Model** maps to the database table and defines relationships.

**Example:**

```php
// Interface
interface OrganizationRepositoryInterface {
    public function getAll(array $filters, int $perPage, string $sortBy, string $sortDir): LengthAwarePaginator;
    public function findById(int $id): ?Organization;
    public function create(array $data): Organization;
    public function update(int $id, array $data): Organization;
    public function delete(int $id): bool;
}

// Repository bindings in AppServiceProvider
$this->app->bind(OrganizationRepositoryInterface::class, OrganizationRepository::class);
```

### Service Layer

Services encapsulate business logic and orchestrate multiple repository calls when needed.

```php
class OrganizationService {
    public function __construct(
        protected OrganizationRepositoryInterface $repository
    ) {}

    public function createOrganization(array $data): Organization
    {
        // Business logic (validation, transformations, etc.)
        return $this->repository->create($data);
    }
}
```

### Policies

Policies enforce authorization rules per model:

| Role | Organization | Users | Tickets | Comments |
|------|-------------|-------|---------|----------|
| **Super Admin** | Full Access | Full Access | Full Access | Full Access |
| **Org Admin** | Own Org Only | Own Org Users | Own Org Tickets | Own Org Comments |
| **Agent** | — | — | Assigned Only | Assigned Ticket Comments |
| **Customer** | — | — | Own Tickets | Own Ticket Comments |

```php
// Super Admin bypass via Gate::before
Gate::before(function (User $user, $ability) {
    return $user->isSuperAdmin() ? true : null;
});
```

### Queues

Email notifications are dispatched to queues for non-blocking execution:

```
Event (TicketCreated) → Listener → Job (SendTicketCreatedEmail) → Mailable
```

### Audit Logs

Model observers automatically capture changes:

```php
// AuditLogObserver hooks into created/updated/deleted
// Stores: user_id, action, model_type, model_id, old_values, new_values
```

### Multi-Tenant Architecture

```
Request → TenantMiddleware → TenantScope (Global) → Filtered Query
```

- **Multitenantable Trait** — Auto-assigns `organization_id` on create; applies global scope on boot.
- **TenantScope** — Adds `WHERE organization_id = ?` to all queries automatically.
- **Super Admin Bypass** — Super admins are excluded from the scope filter.

---

## 🚀 Installation

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL 8.0+ / MariaDB 10.6+
- Node.js >= 18 & NPM
- Laravel CLI

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/your-username/helpdesk-saas.git
cd helpdesk-saas

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Run migrations
php artisan migrate

# 7. Seed the database (roles, permissions, demo data)
php artisan db:seed

# 8. Create storage symlink
php artisan storage:link

# 9. Build frontend assets
npm run build

# 10. Start the development server
php artisan serve
```

---

## ⚙️ Environment Setup

Update your `.env` file with the following configuration:

```dotenv
APP_NAME="Helpdesk SaaS"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=helpdesk_saas
DB_USERNAME=root
DB_PASSWORD=

# Queue (use database driver)
QUEUE_CONNECTION=database

# Mail (example with Mailtrap)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@helpdesk.com"
MAIL_FROM_NAME="Helpdesk SaaS"

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1
```

---

## 🗄 Database Setup

### ER Diagram (Simplified)

```
┌──────────────┐     ┌──────────────┐     ┌──────────────────┐
│ organizations│────<│    users      │────<│     tickets      │
│              │     │              │     │                  │
│ id           │     │ id           │     │ id               │
│ name         │     │ org_id (FK)  │     │ org_id (FK)      │
│ email        │     │ name         │     │ user_id (FK)     │
│ phone        │     │ email        │     │ assignee_id (FK) │
│ is_active    │     │ password     │     │ title            │
│ deleted_at   │     │ deleted_at   │     │ description      │
└──────────────┘     └──────────────┘     │ status           │
                            │              │ priority         │
                     ┌──────┴───────┐     │ deleted_at       │
                     │  role_user   │     └────────┬─────────┘
                     │ user_id (FK) │              │
                     │ role_id (FK) │     ┌────────┴─────────┐
                     └──────────────┘     │ ticket_comments  │
                                          │ id               │
┌──────────────┐     ┌──────────────┐     │ ticket_id (FK)   │
│    roles     │────<│permission_role│    │ user_id (FK)     │
│ id           │     │ role_id (FK) │     │ comment          │
│ name         │     │ perm_id (FK) │     └──────────────────┘
│ description  │     └──────────────┘
└──────────────┘                          ┌───────────────────┐
                     ┌──────────────┐     │ticket_attachments │
                     │  audit_logs  │     │ id                │
                     │ id           │     │ ticket_id (FK)    │
                     │ org_id (FK)  │     │ user_id (FK)      │
                     │ user_id (FK) │     │ file_name         │
                     │ auditable_*  │     │ file_path         │
                     │ event        │     │ mime_type         │
                     │ old_values   │     │ size              │
                     │ new_values   │     └───────────────────┘
                     └──────────────┘
```

### Migration Order

| # | Migration | Table |
|---|-----------|-------|
| 1 | `0000_01_01_000000` | `organizations` |
| 2 | `0001_01_01_000000` | `users`, `password_reset_tokens`, `sessions` |
| 3 | `0001_01_01_000001` | `cache`, `cache_locks` |
| 4 | `0001_01_01_000002` | `jobs`, `job_batches`, `failed_jobs` |
| 5 | `2026_06_02_061945` | `permissions` |
| 6 | `2026_06_02_061945` | `roles` |
| 7 | `2026_06_02_061945` | `tickets` |
| 8 | `2026_06_02_061946` | `permission_role` |
| 9 | `2026_06_02_061946` | `role_user` |
| 10 | `2026_06_02_061946` | `ticket_comments` |
| 11 | `2026_06_02_061947` | `audit_logs` |
| 12 | `2026_06_02_061947` | `ticket_attachments` |
| 13 | `2026_06_02_064833` | `personal_access_tokens` |

### Commands

```bash
# Fresh migration (drops all tables first)
php artisan migrate:fresh

# Fresh migration with seeders
php artisan migrate:fresh --seed

# Rollback last batch
php artisan migrate:rollback
```

---

## 📬 Queue Setup

This application uses the **database** queue driver for processing email notifications asynchronously.

```bash
# 1. Ensure QUEUE_CONNECTION=database in .env

# 2. The jobs table migration is included (0001_01_01_000002)

# 3. Start the queue worker
php artisan queue:work --tries=3 --timeout=90

# For development with auto-restart on code changes:
php artisan queue:listen --tries=3 --timeout=90
```

### Queued Events

| Event | Trigger | Email Sent To |
|-------|---------|---------------|
| `TicketCreated` | New ticket created | Organization Admin |
| `TicketAssigned` | Ticket assigned to agent | Assigned Agent |
| `TicketClosed` | Ticket status → closed | Ticket Creator |

---

## 📁 Storage Link

Ticket attachments are stored in `storage/app/public`. Create the symlink:

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public`, making uploaded files publicly accessible.

### File Upload Configuration

```php
// config/filesystems.php — default disk: 'public'
// Max upload size: configured via php.ini (upload_max_filesize, post_max_size)
```

---

## 📖 API Documentation

### Base URL

```
http://localhost:8000/api
```

### Authentication

All protected endpoints require a **Bearer Token** (Laravel Sanctum):

```
Authorization: Bearer {your-token}
Accept: application/json
Content-Type: application/json
```

### Endpoints

#### 🔐 Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/register` | Register a new user |
| `POST` | `/api/login` | Login and receive token |
| `POST` | `/api/logout` | Revoke current token |
| `POST` | `/api/forgot-password` | Request password reset email |
| `POST` | `/api/reset-password` | Reset password with token |

#### 🏢 Organizations

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/organizations` | List all (paginated) |
| `POST` | `/api/organizations` | Create organization |
| `GET` | `/api/organizations/{id}` | View single |
| `PUT` | `/api/organizations/{id}` | Update |
| `DELETE` | `/api/organizations/{id}` | Soft delete |
| `POST` | `/api/organizations/{id}/restore` | Restore |
| `DELETE` | `/api/organizations/{id}/force-delete` | Permanent delete |

#### 🎫 Tickets

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/tickets` | List (search, filter, sort, paginate) |
| `POST` | `/api/tickets` | Create ticket |
| `GET` | `/api/tickets/{id}` | View ticket |
| `PUT` | `/api/tickets/{id}` | Update ticket |
| `DELETE` | `/api/tickets/{id}` | Delete ticket |
| `POST` | `/api/tickets/{id}/assign` | Assign to agent |
| `POST` | `/api/tickets/{id}/close` | Close ticket |

#### 💬 Comments

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/tickets/{id}/comments` | List comments |
| `POST` | `/api/tickets/{id}/comments` | Add comment |
| `PUT` | `/api/comments/{id}` | Edit comment |
| `DELETE` | `/api/comments/{id}` | Delete comment |

### Response Format

All API responses follow a consistent JSON structure:

```json
// Success (200/201)
{
    "success": true,
    "message": "Resource created successfully.",
    "data": { ... }
}

// Paginated List (200)
{
    "success": true,
    "data": [ ... ],
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 73
    }
}

// Validation Error (422)
{
    "success": false,
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."]
    }
}

// Unauthorized (401)
{
    "message": "Unauthenticated."
}

// Forbidden (403)
{
    "message": "This action is unauthorized."
}
```

### Query Parameters

| Parameter | Example | Description |
|-----------|---------|-------------|
| `search` | `?search=billing` | Search across relevant fields |
| `status` | `?status=open` | Filter by status |
| `priority` | `?priority=high` | Filter by priority |
| `sort` | `?sort=created_at` | Sort by column |
| `dir` | `?dir=desc` | Sort direction (asc/desc) |
| `per_page` | `?per_page=25` | Items per page |
| `page` | `?page=2` | Page number |

---

## 📮 Postman Collection

A ready-to-import Postman collection is included in the project:

```
postman/Helpdesk_SaaS_API.postman_collection.json
```

### Setup

1. Import the collection into Postman.
2. Create an environment with variables:
   - `base_url` = `http://localhost:8000/api`
   - `sanctum_token` = *(auto-populated after login)*
3. Run the **Login** request first — the token is automatically saved.

---

## 📸 Screenshots

> Add screenshots of your application here.

| Screen | Description |
|--------|-------------|
| ![Dashboard](screenshots/dashboard.png) | Admin Dashboard with statistics cards and charts |
| ![Organizations](screenshots/organizations.png) | Organization management list view |
| ![Tickets](screenshots/tickets.png) | Ticket list with filters and search |
| ![Ticket Detail](screenshots/ticket-detail.png) | Ticket detail view with comments |

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

---

## 🛠 Tech Stack

| Technology | Purpose |
|-----------|---------|
| **Laravel 12** | PHP Framework |
| **PHP 8.2+** | Server-side language |
| **MySQL 8.0+** | Database |
| **Laravel Sanctum** | API Authentication |
| **Spatie Permission** | RBAC (Roles & Permissions) |
| **Bootstrap 5** | Frontend UI Framework |
| **Chart.js** | Dashboard Charts |
| **Queue (Database)** | Async Job Processing |

---

## 📄 License

This project is open-sourced software licensed under the [MIT License](LICENSE).

---

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

**Built with ❤️ using Laravel 12**
