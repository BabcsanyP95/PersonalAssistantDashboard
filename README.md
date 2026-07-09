## Docker Setup

Requirements:
- Docker Desktop

Clone the repository.

Create environment files:

cp backend/.env.example backend/.env

Start containers:

docker compose up --build

Run migrations:

docker compose exec laravel php artisan migrate

Application:

Frontend:
http://localhost:5173

Backend:
http://localhost:8000
