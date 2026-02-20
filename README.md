# RPGate - Core Infrastructure

This repository contains the core architecture for **RPGate**, a plural chat and RPG management system. The current stage focuses on a robust, polymorphic database schema designed for high scalability and flexibility.

## 🚀 Features

We have implemented a **Polymorphic Database Architecture** that supports:
* **Users:** Custom UUID-based authentication with `username`, `bio`, and `is_active` status.
* **Rooms:** RPG game rooms owned by users (Masters), supporting private/public access and custom descriptions.
* **Channels:** A channel system using **Polymorphic Relations**. Channels can belong to a `Room` or exist as independent entities (DMs/Groups).
* **Character Sheets:** Flexible sheets using **PostgreSQL JSONB** to support multiple RPG systems (D&D, Tormenta, etc.) without schema changes.
* **Messages:** Messaging table using `BigInt` for primary keys and UUIDs for foreign keys.

## 🛠 Tech Stack

* **Framework:** Laravel 12
* **Database:** PostgreSQL (with JSONB support)
* **Environment:** Laravel Sail (Docker)
* **Architecture:** Polymorphic Relationships & UUID Primary Keys.

## 📐 Database Schema Overview

* **Users** ↔ **Rooms** (One-to-Many via `owner_id`)
* **Rooms** ↔ **Channels** (Polymorphic One-to-Many via `channelable`)
* **Channels** ↔ **Messages** (One-to-Many)
* **Users** ↔ **Channels** (Many-to-Many via `channel_user` pivot)
* **Rooms** ↔ **Sheets** (One-to-Many)



## 🏁 Getting Started

Since we are using **Laravel Sail**, follow these steps to get the environment up and running:

### 1. Clone and Install
```bash
# Clone the repository
git clone <your-repo-url>
cd rpgate

# Install dependencies via Docker
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

### 2. Environment Setup
```bash
cp .env.example .env
# Ensure DB_CONNECTION=pgsql and DB_HOST=pgsql
```

### 3. Start Sail
```bash
./vendor/bin/sail up -d
```

### 4. Database Migration
```bash
./vendor/bin/sail artisan migrate
```

---
Developed by Arthur Reis as part of the RPGate Project.