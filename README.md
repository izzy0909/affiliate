# Affiliate File Upload Project

## About The Project

This project provides a Laravel-based Livewire component allowing users to upload text files containing affiliate data. 
The system parses, validates, filters affiliates based on geographical distance, and presents the filtered list.

Key features:
- Upload and validate `.txt` files using a custom Laravel validation rule.
- Parse JSON lines safely from uploaded files.
- Calculate distances using Haversine formula to filter affiliates within a maximum radius.
- Graceful error handling with user feedback.
- Tailwind CSS frontend styling.

## Tech Stack

- PHP 8.4+
- Laravel (latest stable)
- Livewire
- PHPUnit for testing
- PHP_CodeSniffer & PHP Mess Detector for code quality

## Getting Started

### Prerequisites

- PHP 8.4 or higher
- Docker & Docker Compose
- Laravel Sail
- Node.js 22 or higher & NPM
- Database (if applicable, not required for this component)

### Installation

1. Clone the repository:
    `git clone git@github.com:izzy0909/affiliate.git`

2. Start Sail and services in the background:
   - `docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs`

   - `./vendor/bin/sail up -d`

3. Install PHP dependencies via Sail:
   `./vendor/bin/sail composer install`

4. Copy `.env.example` to `.env` and generate app key:
   `./vendor/bin/sail artisan key:generate`

5. Install NPM dependencies:
   `npm install`

6. Compile assets:
   `npm run dev`

7. Access your app at:
   `http://localhost`

## Running Tests
Run PHPUnit tests using Sail:
`./vendor/bin/sail artisan test`

## Code Quality Tools

### PHP Code Sniffer (PHPCS)

Check coding standards:
`./vendor/bin/sail exec laravel.test vendor/bin/phpcs --standard=phpcs.xml -v app/`

Auto-fix fixable issues:
`./vendor/bin/sail exec laravel.test vendor/bin/phpcbf --standard=phpcs.xml app/`

### Mess Detector
`./vendor/bin/sail exec laravel.test vendor/bin/phpmd app/ text phpmd.xml`