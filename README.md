# 🏪 ELITE POS System

A comprehensive Point of Sale (POS) system built with **Laravel** for retail and inventory management.

[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-Proprietary-green.svg)](LICENSE)

---

## 📌 Table of Contents

- [Features](#-features)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [User Roles](#-user-roles--permissions)
- [Modules](#-modules)
- [Reports](#-reports)
- [Color Scheme](#-color-scheme)
- [Screenshots](#-screenshots)
- [Troubleshooting](#-troubleshooting)
- [License](#-license)

---

## 🚀 Features

### Core Features

| Feature | Description |
|---------|-------------|
| **Inventory Management** | Track products, stock levels, and inventory movements |
| **Sales Management** | Create and manage sales orders, invoices, and receipts |
| **Customer Management** | Maintain customer profiles, credit limits, and purchase history |
| **Supplier Management** | Manage supplier information and credit |
| **Employee Management** | User roles, permissions, and access control |
| **Expense Tracking** | Record and monitor business expenses |
| **Banking Integration** | Manage supplier and beneficiary bank accounts |
| **Reporting & Analytics** | Generate comprehensive reports and KPI dashboards |
| **Multi-Shop Support** | Manage multiple shop locations from one system |

### Advanced Features

- ✅ **Offer Management** - Create buy-one-get-one-free offers with required items
- ✅ **Receiving & Returns** - Manage inventory receiving and return processes
- ✅ **Credit Management** - Track customer and supplier credits
- ✅ **Chip System** - Manage banking chips for transfers
- ✅ **Item Request System** - Request items between shops
- ✅ **Debt Management** - Track and manage customer debts
- ✅ **Emergency Access** - Bypass system restrictions for emergency situations

---

## 📋 Requirements

| Requirement | Version |
|-------------|---------|
| PHP | 8.1 or higher |
| MySQL | 5.7 or higher / PostgreSQL 10+ |
| Composer | Latest stable |
| Node.js | 14.x or higher |
| NPM | 6.x or higher |
| Web Server | Apache / Nginx |

---

## 🛠️ Installation

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/leruma-pos.git
cd leruma-pos 

exit
```
### Step 2: Install PHP Dependencies

```bash
composer install
exit
```

### Step 3: Install NPM Dependencies
```bash
npm install
exit
```

### Step 4: Environment Configuration
```bash
cp .env.example .env

Update the .env file with your database credentials:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=leruma_pos
DB_USERNAME=root
DB_PASSWORD=your_password
exit
```

### Step 5: Generate Application Key
```bash
php artisan key:generate
exit
```

### Step 6: Run Migrations
```bash
php artisan migrate
exit
```

### Start the Server
```bash
php artisan serve
exit
```

## 👤 User Roles & Permissions
Role	Permissions
Admin	Full system access, manage users, view all reports, system settings
Manager	Manage inventory, sales, reports, employees (limited), receiving/returns
Seller	Create sales, view inventory, create invoices, basic customer management

## 📱 Modules
### 📊 Dashboard
Real-time statistics

Sales overview

Quick access to key features

Performance metrics

### 📦 Inventory
Product management (CRUD)

Stock tracking

Receiving management

Return processing

Stock reports

### 💰 Sales
Create new orders

Apply discounts and coupons

Generate invoices

Process payments

Sales history

### 👥 Customers
Customer profiles

Credit management

Purchase history

Debt tracking

KPI dashboard

### 🏦 Banking
Supplier management

Beneficiary management

Bank transfers

Chip management

Deposit reports

### 📈 Reports
Sales reports

Stock reports

Shop reports

Receiving reports

Supplier deposit reports

KPI dashboards

## 📊 Available Reports
Report	Description
- Sales Report	Daily, monthly, and custom date ranges
- Stock Report	Current stock levels and low stock alerts
- Shops Report	Performance across different shops
- Receiving Report	Inventory receiving history
- Supplier Deposit Report	Banking deposits by supplier
- KPI Dashboard	Key performance indicators
- Offered Products Report	Products with active offers
- Full Report	Comprehensive business report

## 🎨 Color Scheme
- Color	Hex Code	Usage
- Navy Blue	#0B1E3D	Primary / Headers
- Amber/Gold	#F59E0B	Secondary / Accents
- Emerald	#059669	Success / Positive
- Rose	#E11D48	Danger / Errors
- Sky Blue	#0284C7	Info / Highlights
