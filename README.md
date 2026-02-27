# VICOBA Multi-tenant Platform (Laravel 10 + Inertia Vue 3)

## Setup
1. `composer install`
2. `cp .env.example .env`
3. Configure MySQL 8 credentials.
4. `php artisan key:generate`
5. `php artisan migrate --seed`
6. `npm install && npm run dev`
7. `php artisan queue:work`
8. `php artisan test`

## Demo Accounts
- Super admin: `admin@vicoba.test` / `Password123!`
- Group admin: `groupadmin@vicoba.test` / `Password123!`
- Treasurer: `treasurer@vicoba.test` / `Password123!`
- Secretary: `secretary@vicoba.test` / `Password123!`
- Loan Officer: `loanofficer@vicoba.test` / `Password123!`
- Auditor: `auditor@vicoba.test` / `Password123!`

## Architecture highlights
- Group scoped tenancy (`group_id` on all financial entities)
- RBAC per group with multi-role support
- Member portal and staff operations in same account
- Audit logs and transactional financial writes
- Export hooks for PDF/Excel
