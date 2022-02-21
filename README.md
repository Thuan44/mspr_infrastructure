# MSPR_INFRASTRUCTURE

Dépôt commun de la MSPR Infrastructure

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/8.x/installation)

Clone the repository

    git clone git@github.com:Thuan44/mspr_infrastructure.git

Switch to the repo folder

    cd mspr_infrastructure

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Run the database migrations (**Set the database connection in .env and create your database before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

If CSS is not correctly loaded

    npm run dev
