#.PHONY: help

help:           ## Show this help.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'


current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))

# 👌 Main

build: deps start ## Build docker container, composer install and up containers


deps: composer-install

# 🐘 Composer

composer-install: CMD=install
composer-update: CMD=update

# Usage example (add a new dependency): `make composer CMD="require --dev symfony/var-dumper ^4.2"`
composer composer-install composer-update:
	@docker run --rm --interactive --tty --volume $(current-dir):/app --user $(id -u):$(id -g) \
		clevyr/prestissimo $(CMD)  \
			--ignore-platform-reqs \
			--no-ansi \
			--no-interaction
			
#composer global require hirak/prestissimo \
#gsingh1/prestissimo $(CMD) \

# 🕵️ Clear cache
# OpCache: Restarts the unique process running in the PHP FPM container
# Nginx: Reloads the server

reload:
	@docker-compose exec php-fpm kill -USR2 1
	@docker-compose exec nginx nginx -s reload

# ✅ Tests

test: ## test your application
	@docker exec -it appto-dev-php make run-tests

coverage: ## phpunit code coverage
	@docker exec -it appto-dev-php make run-coverage

run-tests:
	mkdir -p build/test_results/phpunit
	./vendor/bin/phpstan analyse -l 5 -c etc/phpstan/phpstan.neon src
	./vendor/bin/phpunit --exclude-group='disabled' --log-junit build/test_results/phpunit/junit.xml tests

run-coverage:
	mkdir -p build/test_results/phpunit/coverage
	./vendor/bin/phpunit --coverage-html build/test_results/phpunit/coverage


# 🐳 Docker Compose

start: ## up docker containers
	#@docker-compose -f ./docker/docker-compose.yaml up -d --no-recreate
	@docker-compose -f ./docker/docker-compose.yaml up -d

stop: CMD=stop

destroy: CMD=down

# Usage: `make doco CMD="ps --services"`
# Usage: `make doco CMD="build --parallel --pull --force-rm --no-cache"`
doco stop destroy:
	@docker-compose $(CMD)

rebuild:
	docker-compose -f docker/docker-compose.yaml build --pull --force-rm --no-cache
	make deps
	make start

build-update:
	docker-compose -f docker/docker-compose.yaml build --no-cache
	make deps
	make start

# 🗄️ Data Base
db: ## create database and load fixtures
		@docker exec -it appto-dev-php make init-db

refresh-db: ## rebuild database and load fixtures
		@docker exec -it appto-dev-php make regenerate-db

init-db:
		bin/console d:d:c 
		bin/console d:s:u --force

regenerate-db:
		bin/console d:d:d --force
		bin/console d:d:c
		bin/console d:s:u --force

		
# 📝 Api commands
api-doc: ## generate or update swagger API Doc
		@docker exec -it appto-dev-php vendor/zircote/swagger-php/bin/openapi src/Appto -o public/api_v1.json


