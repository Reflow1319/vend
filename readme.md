# Vend

![Travis](https://travis-ci.org/Finehive/vend.svg?branch=master)
[![StyleCI](https://styleci.io/repos/98275985/shield?branch=master)](https://styleci.io/repos/98275985)

![Vend](https://www.finehive.com/vend/vend_small.jpg)

Vend is a project management tool built on Laravel and Vue.js

## Features
- Create projects, assign project members, set project columns
- Manage project cards (due dates, assign to users, add card description)
- Add tasks, comments, files and time logs to cards
- Create discussion topics to collaborate with team members
- Create messages, attach event and files to messages
- Timetracking
- Filterable timesheets and reports

## Installation

Vend is built on Laravel, the installation process is the same. [See installation on Laravel's website](https://laravel.com/docs/5.4/installation)

After laravel installation, migrate and seed the database:
```
php artisan migrate --seed
```

**Detailed installation:**

After checkout, run the following:
```
composer install
cp .env.example .env
```
*Edit the database settings in .env file*
```
php artisan key:generate
php artisan migrate --seed
```
*Build asset files*
```
npm install
npm run dev // For development or `npm run watch`
npm run prod // For minified version
```

After you can login with the seeded default account:
**admin@admin.com** - **password**

## Development status
Vend is currently under development, do not use in production.

## Security Vulnerabilities

If you discover a security vulnerability within Vend, please send an e-mail to iamzozo@gmail.com. All security vulnerabilities will be promptly addressed.

## License

The Vend open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
