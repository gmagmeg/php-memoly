.PHONY: init up down build logs shell test versions

init:
	test -f src/.env || cp src/.env.example src/.env
	docker compose build app
	docker compose up -d
	grep -q '^APP_KEY=base64:' src/.env || docker compose exec app php artisan key:generate
	docker compose exec app php artisan migrate --force

up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose build

logs:
	docker compose logs -f

shell:
	docker compose exec app bash

test:
	docker compose exec app php artisan test

versions:
	docker compose exec app php -v
	docker compose exec app php artisan --version
	docker compose exec mysql mysql --version
	docker compose exec nginx nginx -v
