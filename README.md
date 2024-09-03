<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# placetogrowms

This project is built with Laravel 11 and InertiaJS.

## Description

This project was created to fulfill the need for a centralized system of microsites designed for online payment processing, directly integrated with payment gateways. 
The main goals of the project include: 
- **Microsite Management:** Create, edit, and manage microsites dynamically. 
- **Payment Integration:** Seamless integration with payment gateways for processing online payments. 
-  **User Management:** Secure user authentication and management functionalities. 
- **Administrative Dashboard:** Provide a comprehensive dashboard for administrators to monitor and manage microsites, payments, and users efficiently.

## Requirements

- PHP >= 8.2
- Laravel >= 11.x
- Node.js >= 20.x
- NPM >= 10.7.x

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/luisJaimeB/placetogrowms.git

2. **Install PHP dependencies:**

   ```bash
   composer install

3. **Install NPM dependencies:**

   ```bash
   npm install

4. **Copy `.env` file:**

   ```bash
   cp .env.example .env

5. **Generate application key:**

   ```bash
   php artisan key:generate

6. **Run migrations and seeders:** (Remember to create your database on your localhost)

   ```bash
   php artisan migrate --seed

7. **Generate application key:**

   ```bash
   npm run dev

## Usage
When running the seeders, the application will create two users: one with administrative privileges and another for customer access. To get started, log in using the following credentials:

**Test cards:** 
https://docs.placetopay.dev/gateway/testing-card

**Standart Users:** 

```bash
Usuario:Admin      correo: luisyi1998@gmail.com  password: 123456
Usuario:Customer   correo: delectuslab@gmail.com  password: 123456 
