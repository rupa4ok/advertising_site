test:
		composer run-script phpunit
serve:
	    php artisan serve
install:
		composer install
up:
		sudo docker-compose up
down:
		sudo docker-compose down
ps:
		sudo docker-compose ps
fresh:
		sudo docker-compose --build -d
env:
		cp .env.example .env
key:
		php artisan key:generate
lint:
		composer run-script phpcs -- --standard=PSR12  app routes tests
fix:
		composer run-script phpcbf -- --standard=PSR12  app routes tests
tests:
		docker-compose exec php-cli vendor/bin/phpunit
