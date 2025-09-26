# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

This is a personal financial management system built with Laravel and Filament. The application allows users to manage bank accounts, transactions, and expense categories through a modern admin panel interface. It's designed for personal use with multi-user support via role-based permissions.

## Development Commands

### Setup and Installation
```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Build assets
npm run build
```

### Development Workflow
```bash
# Start development environment (runs server, queue, logs, and vite concurrently)
composer run dev

# Individual services
php artisan serve                    # Start Laravel server
php artisan queue:listen --tries=1  # Process queues
php artisan pail --timeout=0        # View logs
npm run dev                         # Vite development server
```

### Testing and Quality
```bash
# Run tests
php artisan test
vendor/bin/phpunit

# Code formatting and linting
vendor/bin/pint                     # Laravel Pint (PHP CS Fixer)
```

### Database Operations
```bash
# Fresh migration with seeding
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_table_name

# Run specific seeder
php artisan db:seed --class=DatabaseSeeder
```

### Filament-Specific Commands
```bash
# Create Filament resources
php artisan make:filament-resource ModelName

# Create Filament widgets
php artisan make:filament-widget WidgetName

# Upgrade Filament (automatically runs via composer)
php artisan filament:upgrade

# Create user with super_admin role
php artisan shield:super-admin
```

## Architecture and Code Structure

### Core Domain Models
The application follows Laravel's Eloquent ORM patterns with these primary entities:
- **Account**: Represents bank accounts belonging to users
- **Transaction**: Income/expense records linked to accounts with categorization
- **Category**: Expense categorization system with many-to-many relationship to transactions
- **User**: Authentication with role-based permissions via Spatie Laravel Permission

### Key Relationships
- User → hasMany → Account (one-to-many)
- Account → hasMany → Transaction (one-to-many)
- Transaction → belongsToMany → Category (many-to-many via pivot table)
- User authentication scoped throughout the application

### Filament Admin Architecture
The UI is built entirely with Filament v3, providing:
- **Resources**: CRUD interfaces for models (AccountResource, UserResource)
- **Widgets**: Dashboard statistics and charts (StatsOverview, AccountChart)
- **Relation Managers**: Nested resource management for transactions within accounts
- **Role-based Access**: Integrated with Filament Shield for permissions

### Database Schema Pattern
- All tables follow Laravel naming conventions
- Soft deletes not implemented (hard deletes with cascade constraints)
- Transaction dates stored separately from created_at timestamps
- Decimal fields for monetary amounts
- Foreign key constraints with cascade on delete

### Authentication & Authorization
- Uses Spatie Laravel Permission package
- Filament Shield integration for admin panel permissions
- User scoping applied throughout (users only see their own data)
- Super admin role created via seeding

### Development Patterns (from .cursorrules)
- Final classes for controllers and services (no inheritance)
- Explicit return type declarations
- Repository and Service pattern organization
- Controllers remain thin with business logic in services
- Strict typing enforced (`declare(strict_types=1)`)

### Performance Considerations
- Laravel Octane with RoadRunner configured for production
- Query scoping applied at resource level to prevent N+1 issues
- User-based data isolation for multi-tenancy

## Technology Stack

### Backend
- **PHP**: 8.2+
- **Framework**: Laravel 12.x
- **Admin Panel**: Filament v3.3
- **Database**: MySQL/MariaDB
- **Queue**: Laravel Queues
- **Performance**: Laravel Octane + RoadRunner

### Frontend
- **CSS Framework**: Tailwind CSS v4
- **Build Tool**: Vite
- **Icons**: Blade Grommet Icons

### Key Laravel Packages
- `filament/filament`: Admin panel framework
- `bezhansalleh/filament-shield`: Role and permission management for Filament
- `laravel/octane`: High-performance application server

## Default Credentials
- **Email**: carlosandres0741@gmail.com (from seeder)
- **Password**: carlos#12345
- **Role**: super_admin

## File Structure Notes
- Filament resources located in `app/Filament/Resources/`
- Filament widgets in `app/Filament/Widgets/`
- Models follow standard Laravel conventions in `app/Models/`
- Database migrations show evolution from separate income/expense tables to unified transactions table
- Observers handle transaction-related business logic (TransactionObserver, UserObserver)

## Development Notes
- Currency formatting defaults to Colombian Pesos (COP)
- Transaction types: "expensive" for expenses, "income" for income
- Docker support available with docker-compose.yaml
- SSL certificates included for local HTTPS development
