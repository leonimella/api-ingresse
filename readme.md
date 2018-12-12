# API Ingresse

Master Branch: [![CircleCI](https://circleci.com/gh/leonimella/api-ingresse/tree/master.svg?style=svg)](https://circleci.com/gh/leonimella/api-ingresse/tree/master)

This repository contains a test application for [Ingresse](https://ingresse.com.br). It consist in a simple REST API built in [Laravel](https://laravel.com). It performs CRUD operations using HTTP verbs.

## Topics
 - [Overview](#overview)
 - [Installation](#installation)
 - [Making Requests](#making-requests)
 - [Testing](#testing)
 
## Overview

We manipulate user data through HTTP requests using the GET, POST, PUT and DELETE verbs.

The main route of the application is: `/api/v1/users/`.

If you would like to use [Postman](https://www.getpostman.com/) to perform your requests you will find at the root of the application a file:`api-ingresse.postman_collection.json`. This file contains a collection of requests with parameters to use right away. Just import this file in your collections inside Postman and you are good to go.

_Note_: This Postman's collection makes requests to http://localhost:8080 so make sure that your host is configured properly. If you wish you can alter the server inside each request of the collection. 

## Installation
Clone the repository
```
git clone https://github.com/leonimella/api-ingresse.git api-ingresse
```

Change in to directory
```
cd api-ingresse
```

Create a **.env** file from **.env.example** and fill out the database section like the example below:
```
MySQL, MariaDB

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=mydatabase
DB_USERNAME=root
DB_PASSWORD=secret

----------- OR -------------

SQLite

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/file-db.sqlite
```

_**Recommended**_: Create a **.env.testing** file with another database configuration which will be used during tests. This will prevent unwanted changes in your main db.


Run composer
```
composer install
```
    
Generate key for the application
```
php artisan key:generate
```
Make the migration to build database schema and populate with data
```
php artisan migrate --seed
```

And at last, start the server
```
php artisan serve
```
Open your browser and navigate to [localhost:8000](http://localhost:8000) and make sure you see 'API Ingresse' printed on the screen.

## Making Requests

This application use HTTP verbs to perform CRUD operation in route `/api/v1/users/` and `/api/v1/users/{userId}`. Bellow you can see examples of requests and responses for each route available.

##### List all users
```
REQUEST

http: GET
path: /api/v1/users/
headers: Content-Type: application/json
body: none

-----

RESPONSE

status code - 200
{
    data: [], // Array of users data object
    links: {}, // Navigation links
    meta: {} // Resource metadata
}
```
##### Read data of a specific user
```
REQUEST

http: GET
path: /api/v1/users/{id}
headers: Content-Type: application/json
body: none

-----

RESPONSE

status code - 200
{
    data: {}, // Object with user data
}
```
##### Create a new user
```
REQUEST

http: POST
path: /api/v1/users/
headers: Content-Type: application/json
body: {
    
    // All parameters are required!
    
    "name": "Nome do Usuário",
    "email": "email@email.com",
    "country": "Brasil",
    "state": "SP",
    "city": "São Paulo",
    "address": "Rua Lino de Almeida",
    "number": 390,
    "zipcode": "04399-900",
}

-----

RESPONSE

status code - 201
{
    "data": {
        "id": 22, // New user id
        
        ... // More information
        
        "created_at": {
            "date": "2018-12-10 00:00:00.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "updated_at": {
            "date": "2018-12-10 00:00:00.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        }
    }
}
```
##### Update an existing user
```
REQUEST

http: PUT
path: /api/v1/users/{id}
headers: Content-Type: application/json
body: {
    "state": "RS",
    "number": 093,
}

-----

RESPONSE

status code - 200
{
    "data": {} // Object with updated user data
}
```
##### Delete an existing user
```
REQUEST

http: DELETE
path: /api/v1/users/{id}
headers: Content-Type: application/json
body: none

-----

RESPONSE

status code - 200
{
    "data": {} // Object with deleted user data
}
```
#### Errors
In any case of errors the following response will be delivered.
```
RESPONSE

status code - 400, 404, 500

{
    error: {
        status: 404, // Or 400, 500
        message: "Resource not found." // Or Exception message
    }
}
```

## Testing
As mentioned above, it's recommended that you create a __.env.testing__ file to run the tests in a 'exclusive' environment.

Inside of your __.env.testing__ make sure that the `APP_ENV` parameter is equal to `test` and the `APP_KEY` has the key value. Also change your's database configuration to connect in another database.

For example, using a sqlite file to run testing:
```
DB_CONNECTION=sqlite
DB_DATABASE=database/db-file.sqlite
```
_Note_: Remember to create a `*.sqlite` file and add the path to the `.env.testing`

If you are all set, run the tests commands. 
```
./vendor/bin/phpcs -p ./ && ./vendor/bin/phpunit
```
This will run PHP CodeSniffer and PHPUnit tests respectively.

## Misc
Some more information about this project

- Powered by: [Laravel 5.7](https://laravel.com/docs/5.7)
- Developed with: [PHPStorm](https://www.jetbrains.com/phpstorm/)
- Main OS used: [Ubuntu 18.04](http://releases.ubuntu.com/18.04/)
- API Environment: [Postman](https://www.getpostman.com/)