Quality Management Information System Setup Instructions:

Step 1: Clone the repository
    >> git clone https://github.com/DietherReyes/QMIS.git

Step 2: Go to Project Directory
    >> cd <proj_directory>

Step 3: Install Composer Dependencies
    >> composer install

Step 4: Install NPM Dependencies
    >> npm install

Step 5: Create a copy of .env file
    >> cp .env.example .env

Step 6: Generate APP Encryption Key
    >> php artisan key:generate

Step 7: Create new Database for application
    >> Open MYSQL
    >> CREATE DATABASE <database_name>

Step 9: Add database information to allow laravel to connect to the Database
    DB_HOST
    DB_PORT
    DB_DATABASE
    DB_USERNAME
    DB_PASSWORD

Step 10: Database Migration
    >> php artisan migrate

Step 11: Add System Administrator 
    Note: System Admin Credentials    
        [Username: admin]
        [Password: admin]
        
    >> Open MYSQL
    >> Use <database_name>
    >> INSERT INTO `users` (`id`, `name`, `position`, `functional_unit`, `role`, `permission`, `isActivated`, `username`, `password`, `profile_photo`, `remember_token`, `created_at`, `updated_at`) VALUES
    (1, 'System Administrator', 'System Administrator', 'Management Information System', 'admin', '1,1,1,1,1,1,1,1,1,1,1,1', 1,     'admin', '$2y$10$fqcgfRclbJfcFYRFQUcXPucFjwjplj1gBsDhZxn5Im5Td2q5ILFB2', 'admin.jpg',    'qvWw3CgYU5CUkMspAyu4OLV9kyCmFKoB5CM9M4ALmOx8xG1EfpsucQmmLrds', NULL, '2020-02-11 23:34:10');

Step 11: Link Storage to public
    >> php artisan storage:link
    Go to the public storage <QMIS/public/storage>
    Copy and paste folders from <QMIS/assets> to <QMIS/public/storage>