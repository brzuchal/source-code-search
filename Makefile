.PHONY: coverage cs it test

it: cs test

coverage: vendor
	bin/phpunit --configuration=test/unit/phpunit.xml --coverage-text

cs: vendor
	bin/php-cs-fixer fix --config=.php_cs --diff --verbose --allow-risky=yes

test: vendor
	bin/phpunit --configuration=test/unit/phpunit.xml

vendor: composer.json composer.lock
	composer self-update
	composer validate
	composer install
