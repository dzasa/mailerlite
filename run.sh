#!/bin/bash

docker-compose up -d
until [ "`docker inspect -f {{.State.Status}} test-db`"=="healthy" ]; do
    sleep 0.1;
done;
until [ "`docker inspect -f {{.State.Status}} test-app`"=="healthy" ]; do
    sleep 0.1;
done;
docker exec -i test-app composer install
docker exec -i test-app ./vendor/bin/php-cs-fixer fix -vvv --dry-run
docker exec -i test-db mysql -u root -papp -h 127.0.0.1 app < database/sql/init.sql
docker exec -i test-app php artisan test --env=testing

echo "Open http://127.0.0.1:8001 to open app"
