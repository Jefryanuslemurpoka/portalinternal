# Portal Internal

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Status](https://img.shields.io/badge/Status-In%20Development-yellow.svg)](#)

## Overview

Portal Internal is a web-based enterprise management system built with Laravel framework. This application streamlines internal operations by providing centralized access to company data, task management, and inter-departmental coordination tools.

The system is designed with scalability and maintainability in mind, following Laravel best practices and modern web development standards.

---

## Project Status

> **Current Phase:** Active Development

### Development Progress

- [x] Laravel framework installation and configuration
- [x] Laravel Breeze authentication scaffolding
- [ ] User authentication module (Login/Register)
- [ ] Main dashboard implementation
- [ ] Internal data management system
- [ ] Role-based access control (RBAC)
- [ ] Internal API integration
- [ ] Comprehensive testing suite

---

## Tech Stack

| Component | Technology |
|-----------|------------|
| **Backend Framework** | Laravel 11 |
| **Language** | PHP 8.2+ |
| **Database** | MySQL 8.0+ / MariaDB 10.6+ |
| **Authentication** | Laravel Breeze (Blade) |
| **Frontend** | Blade Templates, Vite, TailwindCSS |
| **Package Manager** | Composer, NPM |
| **Development Server** | Laravel Artisan |

---

## Prerequisites

Before you begin, ensure you have the following installed:

- PHP >= 8.2
- Composer >= 2.5
- Node.js >= 18.x & NPM >= 9.x
- MySQL >= 8.0 or MariaDB >= 10.6
- Git

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/[username]/portalinternal.git
cd portalinternal
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 3. Environment Configuration

```bash
# Create environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

Configure your database credentials in the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_internal
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run migrations:

```bash
php artisan migrate
```

### 5. Start Development Server

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start Vite dev server
npm run dev
```

The application will be available at `http://localhost:8000`

---

## Development Workflow

### Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage
```

### Code Quality

```bash
# Format code (if using Laravel Pint)
./vendor/bin/pint

# Static analysis (if using Larastan)
./vendor/bin/phpstan analyse
```

### Build for Production

```bash
# Optimize application
php artisan optimize

# Build frontend assets
npm run build
```

---

## Project Structure

```
portalinternal/
├── app/                    # Application core
│   ├── Http/              # Controllers, Middleware
│   ├── Models/            # Eloquent models
│   └── Services/          # Business logic
├── database/              # Migrations, Seeders
├── resources/
│   ├── views/             # Blade templates
│   └── js/                # Frontend JavaScript
├── routes/                # Application routes
├── tests/                 # Test suites
└── public/                # Public assets
```

---

## Development Timeline

| Date | Milestone |
|------|-----------|
| Oct 20, 2025 | Laravel Breeze installation |
| Oct 20, 2025 | Authentication routes configuration |
| Oct 20, 2025 | Initial authentication setup |
| *Ongoing* | Core features development |

---

## Contributing

We welcome contributions! Please follow these guidelines:

### How to Contribute

1. **Fork the repository**
2. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```
3. **Commit your changes**
   ```bash
   git commit -m "feat: add your feature description"
   ```
4. **Push to your branch**
   ```bash
   git push origin feature/your-feature-name
   ```
5. **Open a Pull Request**

### Commit Convention

We follow [Conventional Commits](https://www.conventionalcommits.org/):

- `feat:` New feature
- `fix:` Bug fix
- `docs:` Documentation changes
- `style:` Code style changes (formatting)
- `refactor:` Code refactoring
- `test:` Adding tests
- `chore:` Maintenance tasks

---

## Security

If you discover any security-related issues, please email [jefrylemur@gmail.com](mailto:jefrylemur@gmail.com) instead of using the issue tracker.

---

## License

This project is licensed under the [MIT License](LICENSE). You are free to use, modify, and distribute this software with proper attribution.

---

## Contact & Support

**Developer:** Jefryanus Lemur  
**Location:** Jakarta, Indonesia  
**Email:** [jefrylemur@gmail.com](mailto:jefrylemur@gmail.com)  
**Phone:** +62 821-2209-8898

For questions or support, please open an issue on GitHub or contact the developer directly.

---

## Acknowledgments

- Built with [Laravel](https://laravel.com)
- Authentication scaffolding by [Laravel Breeze](https://laravel.com/docs/breeze)
- Styled with [TailwindCSS](https://tailwindcss.com)

---

<p align="center">Made with ❤️ in Jakarta</p>
