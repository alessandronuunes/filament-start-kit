# Filament Start Kit

This is a starter kit project built with Laravel and Filament, designed to provide a solid foundation for building web applications with a pre-configured admin panel, team management, and billing functionalities.

This project is free to use and open-source. You are welcome to fork it, use it for your own projects, and contribute to its improvement.

## Features

This starter kit comes packed with a variety of features to get you up and running quickly:

*   **Laravel 12 Framework:** The latest version of the robust PHP framework.
*   **Filament Admin Panel (v3.3):** A beautiful and extensible admin panel for Laravel.
*   **User Authentication:** Secure user registration and login, powered by Filament Breezy.
*   **Team Management:**
    *   Create and manage teams.
    *   Invite users to teams.
    *   Role-based access control within teams (assumption, can be detailed further).
*   **Billing & Subscriptions (Laravel Cashier):**
    *   Integration with Stripe (default for Cashier) for handling subscriptions and payments.
    *   Manage customer subscriptions and payment methods.
*   **Background Job Processing (Laravel Horizon):** Efficiently manage and monitor your application's queues.
*   **Application Performance Monitoring (Laravel Pulse):** Gain insights into your application's performance, slow queries, and exceptions.
*   **API Authentication (Laravel Sanctum):** Ready for building SPAs or providing secure API endpoints.
*   **Full-Text Search (Laravel Scout):** Easily implement powerful search capabilities (requires a search driver like MeiliSearch or Algolia).
*   **Slugs (Spatie Sluggable):** Automatic generation of SEO-friendly URLs.
*   **Vite Asset Bundling:** Modern and fast frontend asset management.
*   **Tailwind CSS:** A utility-first CSS framework for rapid UI development.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

*   PHP ^8.2
*   Composer
*   Node.js & npm (or yarn)
*   A database server (e.g., MySQL, PostgreSQL, SQLite - SQLite is configured by default)

## Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/your-repository-name.git
    cd your-repository-name
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Install JavaScript dependencies:**
    ```bash
    npm install
    # or
    # yarn install
    ```

4.  **Create your environment file:**
    Copy the example environment file and generate your application key.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5.  **Configure your environment (`.env` file):**
    Update the following settings in your `.env` file:

    *   `APP_NAME`: Your application's name.
    *   `APP_URL`: Your application's URL (e.g., `http://localhost:8000`).
    *   `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: Your database credentials. If you're using SQLite, you might just need to ensure `DB_CONNECTION=sqlite` and the `database/database.sqlite` file exists (it should be created by a composer script).
    *   `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`: Your email sending credentials (e.g., for Mailgun, SendGrid, or use `log` for local development).
    *   **Stripe Keys (for Laravel Cashier):**
        *   `STRIPE_KEY`
        *   `STRIPE_SECRET`
        *   `CASHIER_WEBHOOK_SECRET` (if you plan to use webhooks)
    *   **AWS Credentials (if using S3 for file storage or other AWS services):**
        *   `AWS_ACCESS_KEY_ID`
        *   `AWS_SECRET_ACCESS_KEY`
        *   `AWS_DEFAULT_REGION`
        *   `AWS_BUCKET`

6.  **Run database migrations:**
    This will create the necessary tables in your database.
    ```bash
    php artisan migrate
    ```

7.  **Seed the database (optional):**
    If you have seeders to populate initial data:
    ```bash
    php artisan db:seed
    # You might have specific seeders, e.g., DevelopmentSeeder
    # php artisan db:seed --class=DevelopmentSeeder
    ```

8.  **Build frontend assets:**
    ```bash
    npm run dev
    # or for production
    # npm run build
    ```

9.  **Start the development server and queue worker:**
    The project includes a convenient script to start the server, queue, logs, and Vite dev server concurrently.
    ```bash
    composer dev
    ```
    Alternatively, you can run them separately:
    *   Serve the application: `php artisan serve`
    *   Start the queue worker: `php artisan queue:work` (or `php artisan horizon` if configured)
    *   Start Vite dev server: `npm run dev`

## Configuration Notes

*   **Filament:** Access the admin panel typically at `/admin` (this might vary based on Filament's configuration).
*   **Horizon:** Access the Horizon dashboard at `/horizon`.
*   **Pulse:** Access the Pulse dashboard at `/pulse`.
*   **Scout:** If you plan to use Laravel Scout for search, you'll need to install and configure a search driver (e.g., MeiliSearch, Algolia). Refer to the Laravel Scout documentation.
*   **Cashier:** Ensure your Stripe keys are correctly set up in the `.env` file. You'll also need to configure your products and prices in your Stripe dashboard.

## Contributing

Contributions are welcome! If you'd like to improve this starter kit, please follow these steps:

1.  **Fork the repository.**
2.  **Create a new branch** for your feature or bug fix:
    ```bash
    git checkout -b feature/your-feature-name
    # or
    # git checkout -b fix/your-bug-fix-name
    ```
3.  **Make your changes.**
4.  **Commit your changes** with a clear and descriptive commit message.
5.  **Push your branch** to your forked repository.
6.  **Open a Pull Request** to the original repository.

Please ensure your code follows the existing coding style and includes tests where appropriate.

## License

This project is open-sourced software licensed under the [MIT license](LICENSE.md).
