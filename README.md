# KOKU Watch Store

KOKU is a B2C watch e-commerce application developed for the **Unit 51: E-Commerce and Strategy** assignment. The system provides a customer-facing online watch store and an administration area for managing products, orders, customers, inventory, promotions, reviews, community content, and store operations.

---

## Main Features

### Customer Features

- Browse, search, filter, and sort watches
- View watch details, variants, images, stock, and reviews
- Manage shopping cart and wishlist
- Apply coupons and select shipping options
- Complete guest or registered-customer checkout
- Make test payments through Stripe
- View order history and order status
- Manage profile information and delivery addresses
- Publish verified-purchase product reviews
- Browse and contribute to the Community Wrist Gallery

### Administrator Features

- Dashboard with store information
- Product and product variant management
- Brand and category management
- Inventory management
- Order management
- Customer management
- Coupon and shipping management
- Product review management
- Community content moderation
- Reports and analytics
- Store settings

---

## Technology Stack

| Technology      | Purpose                              |
| --------------- | ------------------------------------ |
| PHP 8.2+        | Server-side programming              |
| Laravel 12      | Backend web application framework    |
| Livewire 4      | Dynamic user interface functionality |
| Alpine.js       | Lightweight client-side interactions |
| Tailwind CSS 4  | User interface styling               |
| DaisyUI         | UI components                        |
| MySQL           | Relational database                  |
| Vite            | Frontend asset building              |
| Node.js and npm | Frontend dependency management       |
| Stripe          | Test payment processing              |

---

## Requirements

Before setting up the project, ensure the following software is installed:

- PHP 8.2 or later with the required Laravel extensions
- Composer
- Node.js and npm
- MySQL
- XAMPP, WAMP, Laragon, or an equivalent local server
- phpMyAdmin or another MySQL database management tool
- Stripe CLI for local Stripe webhook testing

---

# Project Setup

The submitted project includes a configured `.env` file and an exported MySQL database named `koku_database.sql`. The database contains the required database structure, sample data, and testing accounts.

### 1. Extract the Project

Extract the submitted ZIP file and open a terminal in the project directory.

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Frontend Dependencies

```bash
npm install
```

### 4. Start MySQL

Start **MySQL** using XAMPP or your preferred local server.

If phpMyAdmin is being served through XAMPP, start **Apache** as well.

### 5. Create the Database

Open phpMyAdmin and create a new database named:

```text
koku
```

### 6. Import the Database

Select the `koku` database in phpMyAdmin and import the provided:

```text
koku_database.sql
```

The SQL file contains the database structure, sample records, and testing accounts required for system evaluation.

> **Important:** Do not run `php artisan migrate:fresh` after importing the provided database because this will delete the included sample data and testing accounts.

### 7. Database Connection

The included `.env` file is already configured to use the `koku` database.

If a different local MySQL configuration is used, update the database connection settings in `.env` as required.

### 8. Clear Laravel Configuration Cache

```bash
php artisan optimize:clear
```

### 9. Create the Storage Link

```bash
php artisan storage:link
```

This allows publicly accessible uploaded images to be served from Laravel storage.

### 10. Build the Frontend Assets

```bash
npm run build
```

---

# Running the Application

### Terminal 1 — Laravel Server

Run:

```bash
php artisan serve
```

The application will normally be available at:

```text
http://127.0.0.1:8000
```

### Terminal 2 — Vite Development Server

If frontend development mode is required, run:

```bash
npm run dev
```

> If the frontend assets have already been built using `npm run build`, running `npm run dev` is not normally required for basic evaluation.

### Terminal 3 — Queue Worker

Run:

```bash
php artisan queue:work
```

The queue worker processes queued background tasks used by the application.

Keep the required terminals running while evaluating the system.

---

# Sample Login Accounts

The following sample accounts are included in `koku_database.sql` for academic testing.

## Administrator

| Field    | Value             |
| -------- | ----------------- |
| Email    | `admin@koku.test` |
| Password | `password`        |

After signing in, access the administration area at:

```text
http://127.0.0.1:8000/admin
```

## Customer

| Field    | Value                |
| -------- | -------------------- |
| Email    | `customer@koku.test` |
| Password | `password`           |

> These accounts are provided only for local academic testing.

---

# Stripe Test Payment Setup

Stripe is integrated for online payment processing and should be used in **Test Mode** during evaluation.

The required Stripe test API credentials are configured in the included `.env` file for academic testing.

The Stripe configuration uses:

```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

> The included Stripe credentials are test-mode credentials and must not be used for real transactions.

## Stripe Test Card

Stripe's standard successful test card can be used:

| Field       | Test Value                   |
| ----------- | ---------------------------- |
| Card Number | `4242 4242 4242 4242`        |
| Expiry Date | Any future date              |
| CVC         | Any valid three-digit number |
| Postal Code | Any valid value              |

---

## Stripe Webhook Testing

Stripe CLI is required to forward Stripe webhook events to the locally running application.

### 1. Authenticate Stripe CLI

Open a separate terminal and run:

```bash
stripe login
```

This authenticates the locally installed Stripe CLI with a Stripe account.

### 2. Start Webhook Forwarding

Run:

```bash
stripe listen --forward-to http://127.0.0.1:8000/stripe/webhook
```

Stripe CLI will display a webhook signing secret beginning with:

```text
whsec_
```

Update `STRIPE_WEBHOOK_SECRET` in `.env` with the signing secret generated by the Stripe CLI:

```env
STRIPE_WEBHOOK_SECRET=whsec_...
```

Then clear the Laravel configuration cache:

```bash
php artisan optimize:clear
```

Keep the Stripe CLI terminal running while testing payment and webhook functionality.

> **Note:** Stripe CLI authentication is separate from the Stripe test API credentials configured in `.env`.

---

# Manual Testing Guide

## Customer

1. Open the home page and browse the available watches.
2. Test product search, filtering, sorting, and pagination.
3. Open a watch and select an available product variant.
4. Add a product to the shopping cart.
5. Test adding and removing products from the wishlist.
6. Update product quantities in the cart and verify the totals.
7. Continue to checkout and provide or select the required delivery information.
8. Select a shipping method and apply a coupon where applicable.
9. Complete checkout using a Stripe test payment.
10. Verify the order confirmation and order information.
11. Sign in using the sample customer account and review orders, addresses, and profile information.
12. Test product reviews and the Community Wrist Gallery.

## Administrator

1. Sign in using the administrator testing account.
2. Open the administration area at `/admin`.
3. Review the dashboard.
4. Create or edit brands, categories, products, and product variants.
5. Review and update inventory.
6. Review orders and update an order status.
7. Test customer, coupon, and shipping management.
8. Moderate product reviews and Community Wrist Gallery content.
9. Review reports and analytics.
10. Review or update store settings.
11. Confirm that a normal customer cannot access the administration area.

---

# Troubleshooting

### Database Connection Error

Ensure MySQL is running and verify the database configuration in `.env`.

### Tables or Sample Accounts Are Missing

Ensure `koku_database.sql` has been imported into the `koku` database.

### Styles or Scripts Are Missing

Run:

```bash
npm install
npm run build
```

### Uploaded Images Do Not Load

Run:

```bash
php artisan storage:link
```

### Environment Changes Are Not Being Applied

Run:

```bash
php artisan optimize:clear
```

Then restart the Laravel server.

### Stripe Payment or Webhook Fails

Verify the Stripe test credentials in `.env`.

For local webhook testing, ensure Stripe CLI webhook forwarding is running and that the current `whsec_...` signing secret is configured as `STRIPE_WEBHOOK_SECRET`.

### Queue Jobs Are Not Being Processed

Ensure the Laravel queue worker is running:

```bash
php artisan queue:work
```

---

# Submission Contents

The submitted project package contains:

- Laravel application source code
- Composer dependency files
- npm dependency files
- `koku_database.sql`
- Sample database records
- Administrator testing account
- Customer testing account
- Configured `.env` file for local academic testing
- Project setup and testing instructions

---

## Security Notice

The sample accounts and Stripe test credentials included with this project are provided solely for local academic evaluation. All included payment credentials operate in Stripe Test Mode and cannot process real payments.
