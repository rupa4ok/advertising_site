test:
		composer run-script phpunit
serve:
	    php artisan serve
install:
		composer install
up: memory
		sudo docker-compose up
down: memory
		sudo docker-compose down
ps:
		sudo docker-compose ps
mod:
		sudo chmod -R 777 storage
own:
		sudo chown -R rupak:rupak storage
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
memory:
	sudo sysctl -w vm.max_map_count=262144

perm:
	sudo chgrp -R www-data storage bootstrap/cache
	sudo chmod -R ug+rwx storage bootstrap/cache
