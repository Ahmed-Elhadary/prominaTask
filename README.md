# prominaTask
## Run the project
1. Clone repository

    Via Terminal
    ```
        https://github.com/Ahmed-Elhadary/prominaTask.git
        cd prominoTask
        composer install
        npm install
        cp .env.example .env
        php artisan key:generate
    ```
    2. Database (SQL)

    2.1 Create a database with the name `promina`

    2.2 Database Configuration in .env file in the application root
    ``` 
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=promina
        DB_USERNAME=root
        DB_PASSWORD=
    ```
    2. packages (Laravel-medialibrary)
   
         composer require "spatie/laravel-medialibrary"
    ## License
Ahmed Hamam application Copyright © 2024-2024 ProMina company.
