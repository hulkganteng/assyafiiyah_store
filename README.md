# Assyafiiyah Store

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000f?style=for-the-badge&logo=mysql&logoColor=white)

**Assyafiiyah Store** is a robust e-commerce application built with Laravel 11, designed specifically to support **UMKM Pesantren**. It facilitates digital transactions, product management, and order tracking for the community.

## ✨ Features

### 🌍 Public
* **Product Catalog:** Browse available products with ease.
* **Search:** Find specific items quickly.
* **Categories:** Filter products by specific categories.

### 🛒 Buyer
* **Shopping Cart:** Add items and manage quantities.
* **Checkout:** flexible payment options including **COD (Cash on Delivery)** and **Bank Transfer**.
* **Order History:** Track past and current orders.
* **Payment Proof:** Upload transfer receipts for bank transfer verification.

### 🛡️ Admin
* **Dashboard:** Overview of sales and metrics.
* **Management:** CRUD operations for Products and Categories.
* **Order Management:** Process orders and update statuses.
* **Payment Verification:** Verify uploaded payment proofs.
* **Reporting:** Export order data to CSV for accounting.

---

## ✅ Requirements

Ensure your machine meets the following requirements:

* **PHP** >= 8.2
* **Composer**
* **Node.js & NPM**
* **MySQL**

---

## 🚀 Installation

Follow these steps to set up the project locally.

### 1. Clone the Repository
```bash
git clone [https://github.com/username/Assyafiiyah_store.git](https://github.com/username/Assyafiiyah_store.git)
cd Assyafiiyah_store

**Instalasi**
composer install
npm install && npm run build

**Database Setup**
php artisan key:generate
php artisan migrate --seed

**Storage Configuration**
php artisan storage:link
