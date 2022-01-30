#!/bin/bash

DOCKER_DB = template-mysql
DOCKER_PHP = template-php
DOCKER_NETWORK = template-network

OS := $(shell uname)
ifeq ($(OS),Darwin)
	UID = $(shell id -u)
else ifeq ($(OS),Linux)
	UID = $(shell id -u)
else
	UID = 1000
endif

help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

run: ## Start the containers
	docker network create ${DOCKER_NETWORK} || true
	U_ID=${UID} docker-compose up -d

stop: ## Stop the containers
	U_ID=${UID} docker-compose stop

down: ## Stop the containers
	U_ID=${UID} docker-compose down

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) run

build: ## Rebuilds all the containers
	U_ID=${UID} docker-compose build

prepare: ## Runs backend commands
	$(MAKE) composer-install

# Backend commands
composer-install: ## Installs composer dependencies
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_PHP} composer install --no-scripts --no-interaction --optimize-autoloader

sf: ## Runs the migrations
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_PHP} php bin/console $(cmd)

migrations: ## Runs the migrations
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_PHP} php bin/console doctrine:migrations:migrate -n

dump-sql: ## Runs the migrations
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_PHP} php bin/console doctrine:schema:update --dump-sql

force-sql: ## Runs the migrations
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_PHP} php bin/console doctrine:schema:update --force

php-logs: ## Tails the Symfony dev log
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_PHP} tail -f var/log/dev.log
# End backend commands

ssh-db: ## ssh's into the be container
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_DB} /bin/bash

ssh-php: ## ssh's into the be container
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_PHP} /bin/bash

code-style: ## Runs php-cs to fix code styling following Symfony rules
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_PHP} php-cs-fixer fix src --rules=@Symfony

generate-ssh-keys: ## Generates SSH keys for the JWT library
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_PHP} mkdir -p config/jwt
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_PHP} openssl genrsa -passout pass:3e4a1a2be3a2f5b298da720f7f10c6be -out config/jwt/private.pem -aes256 4096
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_PHP} openssl rsa -pubout -passin pass:3e4a1a2be3a2f5b298da720f7f10c6be -in config/jwt/private.pem -out config/jwt/public.pem
