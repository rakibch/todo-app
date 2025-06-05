# 📝 Laravel + Vue.js ToDo App

This app uses Laravel for backend and Vue.js 3 with Vite for frontend.

---

## Installation

1. Clone the repo and enter folder:

```bash
git clone https://github.com/your-username/laravel-vue-todo.git
cd laravel-vue-todo

2. Install backend PHP packages:
composer install
3. Install frontend packages:
npm install
4. Copy environment file and update database info:
cp .env.example .env

Edit .env and set:
DB_DATABASE=your_database
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

5. Generate app key:
php artisan key:generate

6. Run migrations:
php artisan migrate

7. Start Laravel server:
php artisan serve

8. Start frontend dev server:
npm run dev

Open http://localhost:8000 in your browser.


