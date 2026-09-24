# Personal Assistant Dashboard (PAD)

#⚠️ This was my first ever project. Kept here for progress tracking.

A full-stack personal finance management application built with **Laravel**, **Vue 3**, **TypeScript**, and **MySQL**. The application helps users track income, expenses, budgets, savings goals, and overall financial activity through an interactive dashboard.

The project is containerized with **Docker** to provide a consistent, robust development and production environment.

---

## Features

### Authentication
* User registration and login
* Laravel Sanctum API authentication
* Protected routes and session management

### Dashboard & Analytics
* Overview of financial status and total balance tracking
* Income and expense summaries with interactive financial charts

### Transactions & Categories
* Create, update, and delete transactions
* Custom categorization and detailed transaction history

### Budgets & Savings
* Create and manage spending budgets with real-time tracking (`original_amount`, `current_amount`)
* Track savings goals and monitor progress

---

## Tech Stack

### Frontend
* **Vue 3** (Composition API)
* **TypeScript**
* **Vite**
* **Tailwind CSS**
* **Pinia** & **Vue Router**
* **Axios** & **Chart.js**

### Backend
* **Laravel** (PHP 8.4)
* **Laravel Sanctum**
* **MySQL 8.4**
* **REST API**

### Development Environment
* **Docker** & **Docker Compose**

---

## Project Structure

```
PersonalAssistantDashboard/
│
├── api/
│   └── PAD_Api/
│       ├── app/
│       ├── database/
│       ├── routes/
│       ├── Dockerfile
│       └── .env.example
│
├── webapp/
│   └── PAD_App/
│       ├── src/
│       ├── Dockerfile
│       └── package.json
│
├── docker-compose.yml
└── README.md
```

---

# Getting Started & Installation

## Prerequisites
Ensure you have the following installed on your system:
* **Docker** & **Docker Compose**
* **Git**

---

## 1. Clone the repository

```bash
git clone <repository-url>
cd PersonalAssistantDashboard
```

---

## 2. Set up Environment Files

### Backend Configuration
Navigate to or copy the environment template for the Laravel API:
```bash
cp api/PAD_Api/.env.example api/PAD_Api/.env
```
Ensure your `api/PAD_Api/.env` contains the correct Docker MySQL connection settings:
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=finance_tracker
DB_USERNAME=finance_user
DB_PASSWORD=secret
```

### Frontend Configuration
Set up the Vue application environment if applicable:
```bash
cp webapp/PAD_App/.env.example webapp/PAD_App/.env
```

---

## 3. Build and Start Docker Containers

Build all images and start the services in detached mode:

```bash
sudo docker compose up --build -d

sudo docker compose exec laravel php artisan key:generate
sudo docker compose exec laravel chmod -R 775 storage bootstrap/cache
```

### Application Endpoints

| Service      | Access URL             |
| ------------ | ---------------------- |
| Vue Frontend | http://localhost:5180  |
| Laravel API  | http://localhost:8000  |
| MySQL        | localhost:3306         |

---

## 4. Run Database Migrations & Seeders

Once the containers are running and MySQL is fully initialized, run your database migrations and seeders inside the Laravel container:

```bash
sudo docker compose exec laravel php artisan migrate:fresh --seed
```

---

# Common Development Commands

## Laravel (API)
* **Access container shell:**
  ```bash
  sudo docker compose exec laravel bash
  ```
* **Run migrations:**
  ```bash
  sudo docker compose exec laravel php artisan migrate
  ```
* **Clear application cache:**
  ```bash
  sudo docker compose exec laravel php artisan optimize:clear
  ```

## Vue (Frontend)
* **Access container shell:**
  ```bash
  sudo docker compose exec vue bash
  ```
* **Install/update npm packages:**
  ```bash
  sudo docker compose exec vue npm install
  ```

---

# Author

**Babcsány Péter**  
Software Developer Student  
Built as a portfolio project to practice full-stack development with Laravel, Vue, TypeScript, and Docker.
