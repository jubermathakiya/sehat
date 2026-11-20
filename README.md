# Sehat — Multi-Tenant Doctor Management SaaS

Laravel 12 multi-tenant clinic portal with **one MySQL database per customer** and a central master database for Super Admin / subscriptions.

## Theme

Navy `#0B1F3A` · Orange `#F97316` · Teal `#0D9488`

## Requirements

- PHP 8.2+
- MySQL 8/9 (WAMP)
- Composer
- Apache/Nginx (or `php artisan serve`)

## Setup

```bash
cd c:\wamp64\www\juber\sehat
composer install
copy .env.example .env   # already configured for sehat_central
php artisan key:generate
# Create DB: CREATE DATABASE sehat_central;
php artisan migrate --path=database/migrations/central --force
php artisan db:seed --force
php artisan storage:link
```

## Default logins

| Portal | URL | Credentials |
|--------|-----|-------------|
| Super Admin | `/superadmin/login` | `admin@sehat.test` / `Admin@12345` |
| Clinic (demo) | `/clinic/login` | Tenant code from Super Admin + `demo@clinic.test` / `Clinic@12345` |

Demo tenant can be (re)created with:

```bash
php tools/seed_demo_customer.php
```

## Architecture

- **Central DB (`sehat_central`)**: customers, plans, subscriptions, encrypted tenant DB credentials, OTPs, logs
- **Tenant DB (`doctor_customer_XXX`)**: users, roles, companies, contractors, patients, doctors, appointments, medicines, prescriptions, medical histories
- Dynamic connection switching via `TenantConnectionManager`
- Synchronous provisioning on customer create (queue job also available)

## Cron

```bash
* * * * * php artisan schedule:run
```

Scheduled:

- `subscriptions:process-expiry` (daily 01:00)
- `subscriptions:send-reminders` (daily 09:00)

## Key modules

**Super Admin:** dashboard, customers (+ auto DB provision), plans, renew, password reset  

**Clinic:** auth + OTP forgot password, companies, contractors, patients (family), doctors, appointments, medicines, prescriptions (PDF), medical history, reports, profile
