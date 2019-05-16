test:
		composer run-script phpunit
serve:
	    php artisan serve
install:
		composer install
env:
		cp .env.example .env
key:
		php artisan key:generate
lint:
		composer run-script phpcs -- --standard=PSR12  app routes tests
fix:
		composer run-script phpcbf -- --standard=PSR12  app routes tests
test:
		docker-compose exec php-cli vendor/bin/phpunit
