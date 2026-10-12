# SEC Journals

Software Engineering Consortium (SEC) multi-journal platform — full product parity with the JOCRAMS association system, rebranded and extended for five SEC journals.

## Stack

Laravel 13 + Vue 3 SPA + Sanctum + Tailwind

## Local setup

```bash
composer install
npm install
cp .env.example .env   # or keep existing .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
npm run build
php artisan serve --port=8001
```

Default admin: `admin@secjournals.test` / `password`

## Key routes

- `/` — About / home
- `/sec` — Consortium profile, officers, contact
- `/journals` — Five SEC journals
- `/journals/{slug}` — Journal home + editorial leadership
- `/articles` — Published article landing pages
- `/member/*` — Member portal
- `/admin/*` — Admin portal

## Journals

1. SEC Journal of Software Engineering  
2. SEC Journal of Electronic and Electrical Engineering  
3. SEC Journal of Communication and Media Engineering  
4. SEC Journal of Artificial Intelligence and Prompt Engineering  
5. SEC Journal of Management and Business Engineering  
