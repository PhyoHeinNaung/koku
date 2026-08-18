<p align="center">
  <h1 align="center">KOKU</h1>
  <p align="center">
    A modern B2C watch E-Commerce platform built with Laravel.
  </p>
</p>

<p align="center">
  <strong>Laravel</strong> •
  <strong>PHP</strong> •
  <strong>MySQL</strong> •
  <strong>Tailwind CSS</strong> •
  <strong>Stripe</strong>
</p>

---

## About Koku

Koku is a B2C watch E-Commerce website developed using Laravel, PHP, MySQL, JavaScript and Tailwind CSS.

The platform allows customers to discover and purchase watches through product browsing, search, filtering, wishlists, cart and checkout functionality. Stripe is integrated for secure online payment processing.

Koku also includes additional features such as an AI Shopping Assistant, Community Wrist Gallery, product reviews, order tracking and an administrative management interface.

---

## Requirements

Before running the project, ensure the following software is installed:

- PHP 8.x
- Composer
- Node.js and npm
- MySQL
- XAMPP or equivalent local server
- Stripe CLI

---

## Project Setup

### 1. Extract the Project

Extract the submitted project ZIP file and open a terminal inside the project directory.

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Frontend Dependencies

```bash
npm install
```

### 4. Create Environment File

On Windows:

```bash
copy .env.example .env
```

Alternatively, manually copy `.env.example` and rename the copied file to `.env`.

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Create the Database

Create a MySQL database named:

```text
koku
```

### 7. Import the Database

Import the provided SQL file:

```text
koku_database.sql
```

The SQL file contains the database structure and sample data required for testing the system.

### 8. Configure Database Connection

Update the database configuration in `.env` if required:

```env
DB_DATABASE=koku
DB_USERNAME=root
DB_PASSWORD=
```

---

## Running the Application

The application requires several services to run during testing.

### Terminal 1 — Laravel Server

```bash
php artisan serve
```

### Terminal 2 — Frontend Development Server

```bash
npm run dev
```

### Terminal 3 — Queue Worker

```bash
php artisan queue:work
```

The queue worker is required for queued background tasks such as post-payment email processing.

Once the application is running, open:

```text
http://127.0.0.1:8000
```

---

## Stripe Webhook Setup

Stripe is used in test mode for online payment processing.

### 1. Login to Stripe CLI

```bash
stripe login
```

### 2. Forward Stripe Webhooks

Open another terminal and run:

```bash
stripe listen --forward-to http://127.0.0.1:8000/stripe/webhook
```

Stripe CLI will generate a webhook signing secret similar to:

```text
whsec_xxxxxxxxx
```

Copy this value and configure it in `.env`:

```env
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxx
```

Stripe test API credentials must also be configured in the `.env` file.

---

## Sample Login Accounts

The following accounts are included in the provided sample database for system testing.

### Administrator

| Field    | Value             |
| -------- | ----------------- |
| Email    | `admin@koku.test` |
| Password | `password`        |

### Customer

| Field    | Value                |
| -------- | -------------------- |
| Email    | `customer@koku.test` |
| Password | `password`           |

> These credentials are provided for academic testing purposes only.

---

## Stripe Test Payment

Stripe should be used in **Test Mode** while evaluating the system.

| Field       | Test Value               |
| ----------- | ------------------------ |
| Card Number | `4242 4242 4242 4242`    |
| Expiry Date | Any future date          |
| CVC         | Any valid 3-digit number |
| Postal Code | Any valid value          |

---

## Main Features

### Customer Features

- Product browsing, searching, filtering and sorting
- Detailed watch and variant information
- Shopping cart
- Wishlist
- Coupon application
- Guest checkout
- Registered customer checkout
- Stripe online payment
- Order confirmation
- Order history and tracking
- Product reviews
- Community Wrist Gallery
- Profile and address management

### Administration Features

- Admin dashboard
- Product management
- Product variant management
- Brand and category management
- Inventory management
- Order management
- Customer management
- Coupon management
- Review management
- Community content management
- Reports and analytics

---

## Technologies Used

| Technology    | Purpose                           |
| ------------- | --------------------------------- |
| Laravel       | Backend web application framework |
| PHP           | Server-side programming           |
| MySQL         | Relational database               |
| JavaScript    | Client-side functionality         |
| Tailwind CSS  | User interface styling            |
| Stripe        | Online payment processing         |
| Stripe CLI    | Local webhook testing             |
| Laravel Queue | Background job processing         |

---

## Submission Notes

This project is submitted as part of the **Unit 51: E-Commerce and Strategy** assignment.

The submitted project package includes:

- Laravel source code
- SQL database file
- Sample database records
- Administrator test account
- Customer test account
- Environment configuration example
- Project setup and testing instructions
