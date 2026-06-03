# 📦 Inventory Management System

A web-based Inventory Management System built with **Laravel**, **MySQL**,
and **Bootstrap 5**. The system helps businesses monitor products,
track stock levels, manage suppliers, and generate inventory reports
in real time.

---

## 👨‍💻 Developers

| Name | Role | GitHub |
|------|------|--------|
| Fernando, Estifanie G. |  Frontend Developer | @httpaniii1 |
| Molano, Zheena Collene V. | Backend Developer | @ziinigang |
| Molina, Joseph Jr. R. | Full Stack Developer | @jooo-zip |

---

## 🔍 System Overview

The Inventory Management System provides the following core features:

- **Authentication** — Secure login and logout with role-based access
  (Admin and Staff roles)
- **Products** — Full CRUD operations with SKU tracking, category
  management, and low stock indicators
- **Suppliers** — Supplier directory with supply history and
  active/inactive status management
- **Inventory** — Real-time stock level monitoring with color-coded
  status indicators (In Stock / Low Stock / Out of Stock)
- **Stock Movements** — Immutable audit trail for all stock changes
  (Stock In, Stock Out, Adjustments) with user tracking
- **Reports** — Export inventory, movement, and supplier data as
  PDF, Excel (.xlsx), or CSV
- **REST API** — Full RESTful API with token-based authentication
  via Laravel Sanctum, testable with Postman
- **Dashboard** — Live statistics, 7-day movement chart, stock
  status donut chart, and low stock alerts

---

## 🛠️ Tech Stack

| Layer      | Technology                     |
|------------|-------------------------------|
| Backend    | PHP 8.x + Laravel 11          |
| Frontend   | Blade Templates + Bootstrap 5  |
| Database   | MySQL                          |
| API Auth   | Laravel Sanctum                |
| PDF Export | barryvdh/laravel-dompdf        |
| Excel/CSV  | maatwebsite/laravel-excel      |
| Charts     | Chart.js                       |
| Version Control | Git + GitHub              |

---

## 🗄️ Database Design

The system uses **5 tables** with the following relationships:

**Relationships:**
- `Supplier` → hasMany → `Products`
- `Product`  → hasOne  → `Inventory`
- `Product`  → hasMany → `StockMovements`
- `User`     → hasMany → `StockMovements`

---

## ⚙️ Installation & Setup

### Requirements
- PHP >= 8.2
- Composer
- MySQL
- Git

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/ziinigang/inventory-system.git
cd inventory-system
```

**2. Install PHP dependencies**
```bash
composer install
```

**3. Copy environment file**
```bash
cp .env.example .env
```

**4. Generate application key**
```bash
php artisan key:generate
```

**5. Configure your database**

Edit `.env` and set your MySQL credentials:
```env
DB_DATABASE=inventory_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

**6. Run migrations and seeders**
```bash
php artisan migrate:fresh --seed
```

**7. Start the development server**
```bash
php artisan serve
```

**8. Visit the application**

---

## 🔐 Default Login Credentials

| Role  | Email                    | Password     |
|-------|--------------------------|--------------|
| Admin | admin@inventory.com      | password123  |
| Staff | staff@inventory.com      | password123  |

> ⚠️ Change these credentials immediately in a production environment.

---

## 🌐 API Usage

The system includes a RESTful API. Authenticate first to get a token:

```http
POST /api/login
Content-Type: application/json

{
    "email": "admin@inventory.com",
    "password": "password123"
}
```

Use the returned token as a Bearer token for all subsequent requests:

```http
GET /api/products
Authorization: Bearer YOUR_TOKEN_HERE
```

**Available endpoints:**

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST   | /api/login | Get API token |
| GET    | /api/products | List products |
| POST   | /api/products | Create product |
| GET    | /api/products/{id} | Get product |
| PUT    | /api/products/{id} | Update product |
| DELETE | /api/products/{id} | Delete product |
| GET    | /api/suppliers | List suppliers |
| POST   | /api/stock-movements | Record movement |
| GET    | /api/inventories | List inventory |

Full API documentation available in `/docs/api.md`

---

## 🚀 Deployment

**Live URL:** [http://inventory-system.site.je/](https://your-deployment-link.com)

Deployed on **InfenityFree** using MySQL as the production database.

---

## 📋 Features Checklist

- ✅ Authentication (Login, Logout, Sessions, Password Hashing)
- ✅ CRUD Operations (Products, Suppliers)
- ✅ Database Relationships (One-to-Many, One-to-One)
- ✅ RESTful API (GET, POST, PUT/PATCH, DELETE)
- ✅ Blade Master Layout (x-layout, x-nav-link)
- ✅ Middleware Protection (Admin routes, Guest restrictions)
- ✅ Auto-Generated Reports (PDF, Excel, CSV)
- ✅ GitHub Repository with commit history
- ✅ Deployment on Railway

---

## 📄 License

This project was developed as a Final Project for the subject
**Web Development using Laravel** — Academic Year 2025–2026.