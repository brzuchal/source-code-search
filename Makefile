.PHONY: coverage cs it test dev dev-bash dev-stop prod prod-bash prod-stop vendor

it: cs test

dev:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml build
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml up -d
	docker-compose -f docker-compose.yml -f docker-compose.prod.yml exec php make vendor
	docker-compose -f docker-compose.yml -f docker-compose.prod.yml ps

dev-bash:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml exec php bash

dev-stop:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml kill

prod:
	docker-compose -f docker-compose.yml -f docker-compose.prod.yml build
	docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
	docker-compose -f docker-compose.yml -f docker-compose.prod.yml ps

prod-bash:
	docker-compose -f docker-compose.yml -f docker-compose.prod.yml exec php bash

prod-stop:
	docker-compose -f docker-compose.yml -f docker-compose.prod.yml kill

coverage: vendor
	bin/phpunit --configuration=test/unit/phpunit.xml --coverage-text

cs: vendor
	bin/php-cs-fixer fix --config=.php_cs --diff --verbose --allow-risky=yes

test: vendor
	bin/phpunit --configuration=test/unit/phpunit.xml
	bin/phpunit --configuration=test/integration/phpunit.xml

vendor: composer.json composer.lock
	composer self-update
	composer validate
	composer install
