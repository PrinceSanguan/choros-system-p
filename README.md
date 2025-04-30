# PNP E-Mapping System

This is the PNP E-Mapping System for the Anti-Partisan Communist Local Support System. The application provides mapping and data tracking capabilities for the Philippine National Police.

## Features

- User authentication (admin and regional users)
- Dashboard with regional statistics
- ISO Operations management
- Sightings tracking
- Firearms inventory
- CTG member tracking
- PAG member tracking
- Surrendered individuals management
- Interactive map visualization

## Installation

1. Clone the repository
   ```
   git clone <repository-url>
   cd pnp-emapping
   ```

2. Install composer dependencies
   ```
   composer install
   ```

3. Create a copy of your .env file
   ```
   cp .env.example .env
   ```

4. Generate an app encryption key
   ```
   php artisan key:generate
   ```

5. Create an empty database for the application

6. In the .env file, add database information to allow Laravel to connect to the database
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pnp_emapping
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. Migrate the database
   ```
   php artisan migrate
   ```

8. Seed the database with default users
   ```
   php artisan db:seed
   ```

9. Start the local development server
   ```
   php artisan serve
   ```

## Default User Accounts

The system comes with the following default accounts:

- Admin: username: `APCSLADMIN`, password: `APCSL`
- Region 4A: username: `RMFB4A`, password: `APCSL`  
- Region 4B: username: `RMFB4B`, password: `APCSL`
- Region 5: username: `RMFB5`, password: `APCSL`

## System Requirements

- PHP >= 8.1
- Laravel 10
- MySQL or compatible database

## Security

This system contains sensitive police data. Please ensure proper security measures are in place:

1. Change the default passwords immediately after installation
2. Set up HTTPS for secure connections
3. Implement proper backup procedures
4. Restrict server access to authorized personnel only
