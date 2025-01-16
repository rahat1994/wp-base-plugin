# WP Base Plugin

WP Base Plugin is a WordPress plugin that relies on Vite, Vue, and Composer. This plugin provides a modern development environment for building WordPress plugins with the latest web technologies.

## Requirements

-   WordPress 6.0 or higher
-   PHP 7.4 or higher
-   Node.js 18 or higher
-   Composer

## Installation

1. Clone the repository into your WordPress plugins directory:

    ```
    git clone https://github.com/your-repo/wp-base-plugin.git wp-content/plugins/wp-base-plugin
    ```

2. Navigate to the plugin directory:

    ```
    cd wp-content/plugins/wp-base-plugin
    ```

3. Install PHP dependencies using Composer:

    ```
    composer install
    ```

4. Install JavaScript dependencies using npm:

    ```
    npm install
    ```

5. Build the assets using Vite:

    ```
    npm run build

    or

    npm run dev
    ```

6. Activate the plugin through the WordPress admin dashboard.

## Development

To start the development server with hot module replacement, run:
