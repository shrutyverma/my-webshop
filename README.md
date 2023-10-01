# My Webshop Project

## Introduction
This repository contains a web application for a fictitious webshop built using Laravel. The project utilizes the XAMPP stack for local development. It includes features like managing orders, customers, and products, as well as payment processing.

## Prerequisites
Before you begin, ensure you have met the following requirements:
- XAMPP (or a similar web server stack) installed on your local machine.
- Composer (PHP package manager) installed globally.
- A modern web browser.
- MySQL database management tool (e.g., phpMyAdmin).

## Getting Started
Follow these steps to set up and run the project locally:

1. Clone the repository to your local machine:

   ```bash
   git clone https://github.com/shrutyverma/my-webshop.git
2. Navigate to the project directory:

    ```bash
    cd my-webshop
3. Install project dependencies using Composer:
   
    ```bash
    composer install
4. Create a copy of the .env.example file and rename it to .env. Update the database connection details in this file according to your local database setup.
5. Generate a new application key:

    ```bash
    php artisan key:generate
6. Create an empty MySQL database for your project using phpMyAdmin or a similar tool.
7. Run database migrations to create tables:

    ```bash
    php artisan migrate
8. Start the development server:

    ```bash
    php artisan serve
9. Open your web browser and access the application at http://localhost:8000.
    
## Usage
- Create, view, edit, and delete orders, customers, and products.
- Attach products to orders.
- Pay orders using the integrated payment processing service.

## API Endpoints
- `http://localhost:8000/orders` - CRUD operations for orders(On web browser).
- `/api/orders/{id}/add` - Attach a product to an order(postman for API Testing).
- `/api/orders/{id}/pay` - Submit and pay for an order(postman for API Testing).
