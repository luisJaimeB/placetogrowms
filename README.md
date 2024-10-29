<h1 style="
    font-size: 60px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    background: linear-gradient(90deg, #4CAF50, #2E8B57);
    -webkit-background-clip: text;
    color: transparent;
    text-align: center;
    margin-bottom: 20px;
">
    placetogrowms
</h1>



This project is built with Laravel 11 and InertiaJS.

## Description

This project was created to fulfill the need for a centralized system of microsites designed for online payment processing, directly integrated with payment gateways. 
The main goals of the project include: 
- **Microsite Management:** Create, edit, and manage microsites dynamically. 
- **Payment Integration:** Seamless integration with payment gateways for processing online payments. 
-  **User Management:** Secure user authentication and management functionalities. 
- **Administrative Dashboard:** Provide a comprehensive dashboard for administrators to monitor and manage microsites, payments, and users efficiently.

## Requirements

- PHP >= 8.3
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

8. **Add your gateway enviroment variables**

   ```bash
   PACETOPAY_ENDPOINT=""
   PLACETOPAY_LOGIN=""
   PLACETOPAY_SECRET=""

9. **Add your mail enviroment variables**

   ```bash
   MAIL_MAILER=
   MAIL_HOST=
   MAIL_PORT=
   MAIL_USERNAME=
   MAIL_PASSWORD=

## Usage
When running the seeders, the application will create two users: one with administrative privileges and another for customer access. To get started, log in using the following credentials:

**Test cards:** 
https://docs.placetopay.dev/gateway/testing-card

**Note:**
When you create a Subscription type microsite, it will send you to the subscription plans view, so that you can create them from there.

**Standart Users:** 

```bash
Usuario:Admin      correo: luisyi1998@gmail.com  password: 123456
Usuario:Customer   correo: delectuslab@gmail.com  password: 123456 
```

## Dependencies
The Spartan plugin from placetopay is used. As a recommendation, use the develop branch and validate the package.json.
URL: https://github.com/placetopay-org/spartan-vue/tree/develop

## CSV to Invoices
The .csv file for importing invoices must follow the following format:

```
microsite_id,order_number,identification_type_id,identification_number,debtor_name,email,description,currency_id,amount,expiration_date,surcharge_date,surcharge_rate,percent,additional_amount
1,inv-321654,1,1007182654,Jhon doe,pruebacertificacionp2p@gmail.com,description pruebas,1,1000,2025-10-31,2024-11-22,percent,10,1000
1,inv-123456,1,1007182655,Luis Jaime,pruebacertificacionp2p@gmail.com,description pruebas,1,10000,2025-11-29,2024-11-23,percent,10,1000
1,inv-987654,1,1007182656,Luis Barbosa,delectuslab@gmail.com,description pruebas,1,15000,2025-11-30,2024-11-21,percent,10,1000
1,inv-654987,1,1007182657,Jhon Pruebas,luisyi1998@gmail.com,description pruebas,1,12600,2025-11-25,2024-11-22,percent,10,1000
```
