# prinikfront.pleer.ru



## Getting started
docker compose -f docker-compose.dev.yml up -d

docker compose -f docker-compose.dev.yml exec php-fpm ./vendor/bin/doctrine orm:schema-tool:create

docker compose -f docker-compose.dev.yml exec php-fpm ./vendor/bin/phpunit
