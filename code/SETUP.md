# Development Set-up
## Prerequisites
* Composer
* Node Package Manager
* PHP 7.3.10
* MySQL 5.7.24
* Laravel 5.4.22
* Ubuntu 16.04 or better
* Latest stable version of Google Chrome or Mozilla Firefox

## Instructions

1. Clone the repository
    ```
    git clone https://github.com/DietherReyes/QMIS.git
    ```
2. Go to Project Directory
```
    cd <proj_directory>
```
3. Install Composer Dependencies

    >> composer install

4. Install NPM Dependencies

    >> npm install

5. Create a copy of .env file

    >> cp .env.example .env

6. Generate APP Encryption Key

    >> php artisan key:generate

7. Create new Database for application

    >> Open MYSQL
    >> CREATE DATABASE <database_name>

8. Add database information to allow laravel to connect to the Database

    DB_HOST
    DB_PORT
    DB_DATABASE
    DB_USERNAME
    DB_PASSWORD

9. Change Application name in environment variables

    APP_NAME = QMIS

10. Database Migration

    >> php artisan migrate

11. Add System Administrator 

    Note: System Admin Credentials    
        [Username: admin]
        [Password: admin]
        
    >> Open MYSQL
    >> Use <database_name>
    >> INSERT INTO `users` (`id`, `name`, `position`, `functional_unit`, `role`, `permission`, `isActivated`, `username`, `password`, `profile_photo`, `remember_token`, `created_at`, `updated_at`) VALUES
    (1, 'System Administrator', 'System Administrator', 'Management Information System', 'admin', '1,1,1,1,1,1,1,1,1,1,1,1', 1,     'admin', '$2y$10$fqcgfRclbJfcFYRFQUcXPucFjwjplj1gBsDhZxn5Im5Td2q5ILFB2', 'admin.jpg',    'qvWw3CgYU5CUkMspAyu4OLV9kyCmFKoB5CM9M4ALmOx8xG1EfpsucQmmLrds', NULL, '2020-02-11 23:34:10');

12. Link Storage to public

    >> php artisan storage:link
    
    Go to the public storage <QMIS/public/storage>
    Copy and paste folders from <QMIS/assets> to <QMIS/public/storage>
