# API Ingresse

Teste application for [Ingresse](https://www.ingresse.com/)

## Overview
 This application is a simple REST API build with Laravel that show, create, update and destroy (CRUD) user data. The CRUD routes are built using HTTP verbs.
 
## Resources

- `test-api-ingresse.postman_collection.json` file. Collection of requests to test the application via [Postman](https://postman.co). If you have Postman installed, just import this file to your collections.
- Main route: `/api/v1/users/`

## Install
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

_**Optional**_: I recommend that you create a **.env.testing** file with another database configuration which will be used during HTTP Tests. This will prevent unwanted changes in your main db.


Run composer
```
composer install
```
    
Generate key for the application
```
php artisan generate:key
```
Make the migration to build database schema
```
php artisan migrate
```
And at last, start the serve
```
php artisan serve
```
Open your browser and navigate to [localhost:8000](http://localhost:8000) and make sure you see 'API Ingresse' printed.

## Making Requests

This application use HTTP verbs to perform CRUD operation in route `/api/v1/users/` and `/api/v1/users/{userId}`

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
##### Get a specific user
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
##### Create user
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
##### Update user
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
##### Delete user
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