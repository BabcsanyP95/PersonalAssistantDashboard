# Personal Assistant Dashboard (PAD)

A full-stack personal finance management application built with **Laravel**, **Vue 3**, **TypeScript**, and **MySQL**. The application helps users track income, expenses, budgets, savings goals, and overall financial activity through an interactive dashboard.

The project is containerized with **Docker** to provide a consistent development environment.

---

## Features

### Authentication

* User registration and login
* Laravel Sanctum API authentication
* Protected routes
* Password management

### Dashboard

* Overview of financial status
* Total balance tracking
* Income and expense summaries
* Financial charts and visualizations

### Transactions

* Create, update, and delete transactions
* Categorize transactions
* Track income and expenses
* View transaction history

### Categories

* Create and manage transaction categories
* Organize financial records

### Budgets

* Create and manage spending budgets
* Monitor budget limits

### Savings

* Track savings goals
* Monitor progress

---

## Tech Stack

### Frontend

* Vue 3
* TypeScript
* Vite
* Tailwind CSS
* Pinia
* Vue Router
* Axios

### Backend

* Laravel
* PHP
* Laravel Sanctum
* MySQL
* REST API

### Development Environment

* Docker
* Docker Compose

---

## Project Structure

```
PersonalAssistantDashboard/
│
├── api/
│   └── PAD_Api/
│       ├── app/
│       ├── routes/
│       ├── database/
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

# Running the Project

## Requirements

Install:

* Docker Desktop
* Git

---

## 1. Clone the repository

```bash
git clone <repository-url>

cd PersonalAssistantDashboard
```

---

## 2. Create environment files

Backend:

```bash
cp api/PAD_Api/.env.example api/PAD_Api/.env
```

Frontend:

```bash
cp webapp/PAD_App/.env.example webapp/PAD_App/.env
```

---

## 3. Configure environment variables

Update the backend `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=finance_tracker
DB_USERNAME=finance_user
DB_PASSWORD=
```

Make sure these values match your Docker MySQL configuration.

---

## 4. Start Docker containers

Build and start all services:

```bash
docker compose up --build
```

The application services will run:

| Service      | URL                   |
| ------------ | --------------------- |
| Vue Frontend | http://localhost:5173 |
| Laravel API  | http://localhost:8000 |
| MySQL        | localhost:3306        |

---

## 5. Run database migrations

Open another terminal:

```bash
docker compose exec laravel php artisan migrate
```

To reset and seed the database:

```bash
docker compose exec laravel php artisan migrate:fresh --seed
```

---

# Docker Services

The application uses three containers:

## Laravel API

Provides:

* REST API endpoints
* Authentication
* Business logic
* Database communication

## Vue Frontend

Provides:

* User interface
* Dashboard components
* Client-side routing
* API communication

## MySQL Database

Stores:

* Users
* Transactions
* Categories
* Budgets
* Savings data

---

# API Authentication

Authentication is handled using Laravel Sanctum.

The flow:

1. User registers or logs in
2. Laravel creates an authentication token
3. Frontend stores the token
4. Protected API requests include the token

Example:

```
Authorization: Bearer <token>
```

---

# Development Commands

## Laravel

Access the Laravel container:

```bash
docker compose exec laravel bash
```

Run migrations:

```bash
php artisan migrate
```

Clear cache:

```bash
php artisan optimize:clear
```

---

## Vue

Access the frontend container:

```bash
docker compose exec vue bash
```

Install packages:

```bash
npm install
```

Run development server:

```bash
npm run dev
```

---

# Future Improvements

Possible future additions:

* Recurring transactions
* Advanced analytics
* Export financial reports
* Dark mode
* Mobile responsive improvements
* Notification system
* Deployment configuration

---

# Author

**Babcsány Péter**

Software Developer Student

Built as a portfolio project to practice full-stack development with Laravel, Vue, TypeScript, and Docker.
