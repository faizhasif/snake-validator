## Snake Validator

This project assumes Docker is installed on your machine

Navigate to the project root folder on your machine and run the following command

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

This will install all PHP dependencies without having PHP binaries installed on your machine


## Initializing Projects

### Project Key
After the dependencies are installed, run the following command to generate a unique key for the project

```
./vendor/bin/sail artisan key:generate
```

### Database
This project uses SQLite to store all data. Create a new SQLite file by running the following command

```
touch database/database.sqlite
```

## Running Projects
Run the following command to start the project

```
./vendor/bin/sail up -d
```

### Database Migration
The SQLite database file has been created but at the moment, it doesn't have any schemas in it. Run the following command to migrate the database

```
./vendor/bin/sail artisan migrate
```

## Interacting with the project
In your browser, navigate to `localhost` to verify that the server for this project is up and running correctly.

The 2 exposed API can be accessed at the following URL
New Game - `localhost/api/new`
Validate - `localhost/api/validate`
