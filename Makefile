up:
	docker compose -f docker-compose.dev.yml up -d
down:
	docker compose -f docker-compose.dev.yml down
test:
	php artisan test --coverage --min=75.3
serve:
	php artisan serve --port=12000
