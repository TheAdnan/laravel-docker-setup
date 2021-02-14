
# Interview task

## Installing and configuring the project

- Requirements:
    - PHP 7.2
    - MySQL 5.7
    - Composer
    - Docker compose (optional)

### Running app in docker
If you choose to install and run the project with docker, clone the repository, go to the src folder and run
`composer install`

And then go to the root folder of the repository and run the following command:
`docker-compose up -d --build`

After the containers are up and running, you just need to run the migration and you're all set:
`docker-compose exec php php /var/www/artisan migrate:fresh --seed`

The postman collection needs to be updated to the servername that is set in the `docker-compose.yml` file, but by default the app will be served on `http://localhost:8088/`

Depending on the OS, you might need to change the permissions of the `src/storage` and `src/bootstrap/cache` folders to __755__.


### Creating the database
Alternatively, if you don't wanna install the project via docker, we will have to do it the old fashioned way. First of all, log into your MySQL console (or Database manager of choice) and create the `symphony` database (doesn't have to be called like this, but do edit the `.env` file if you choose another DB name)

`mysql> CREATE DATABASE symphony;`

After the database was created, update the `.env` file with your database username and password

### Run database migrations

Go into the project root folder and run the following command:

`php artisan migrate:fresh --seed`

This will run the migration and populate the database with test data.

### Run project

To run the API/app, execute the following:

`php artisan serve`

This will serve the webapp on `http://127.0.0.1:8000/`

## API routes

The postman collection and examples are attached in the `public` folder of the project.
To view the API docs, run:
`{server_name}/api/routes`


## Improvements

Things that could need to be improved:
* Link crawling could be improved, or rather the parsing of the scraped content could be improved (filtering out conjunctions, prepositions and other sorts of grammatical obstacles)
* Hadn't had time to count the occurrences of the tags returned from other users for an existing link
* This documentation definitely needed to be written a bit more in depth
* Unit tests should be written (if I had the time, definitely would've gone with TDD)
* Email verification was not added (basically all set up for email verification, just needed to add the provided EmailVerification service)
