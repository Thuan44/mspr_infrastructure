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

You can edit CSS style in resources/sass/app.scss file. To see the changes automatically after saving

    npm run watch

## Auth Flow
```mermaid
flowchart TD;
    A[User submit login form] --> B{Exist in AD ?};
    B-->|Yes| N{Login/Password ok ?};
    N-->|Yes| F{First connection ?};
    N:::decision-->|No| X;
    F:::decision-->|Yes| G[Registering of User-Agent and Origin IP];
    F-->|No| C{IP from France ?};
    B:::decision-->|No| X[Login Failed !];
    C:::decision-->|Yes| D{IP matching ?};
    C-->|No| X;
    G-->J;
    E-->I{Link clicked ?};
    I-->M{Token verification ok ?}-->|Yes| J;
    M:::decision-->|No| X;
    I:::decision-->|No| X;
    D-->|Yes| Y{User-Agent matching ?};
    D:::decision-->|No| V[Send alert email];
    V-->Y;
    Y-->|Yes| J;
    Y:::decision-->|No| E[Send confirmation email];
    J[Send 2FA code by mail] --> K{Code OK ?};
    K:::decision-->|Yes| H[Successfully logged in !];
    K-->|No| X;
 ```
