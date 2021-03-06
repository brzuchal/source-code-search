# Source Code Search

Production-ready simple REST API for searching in all Github code.

[![Build Status](https://api.travis-ci.org/brzuchal/source-code-search.svg?branch=master)](https://travis-ci.org/brzuchal/source-code-search)

## Requirements

* Must be easy to replace, so I can change provider if I some day want to use GitLab or Bitbucket, or all of them
* Must have an endpoint that accepts a query, and returns the paginated result. One hit (result) must comprise of owner name, repository name and file name
* The number of hits per page should be 25 by default, but must be changeable by a query string parameter
* The page number should be changeable by a query string parameter
* The sorting should be by score, but must be changeable by a query string parameter

## Config

All default setting can be setup in `config/config.php` file

```php
return [
    'sort_field' => 'BEST_MATCH',
    'sort_order' => 'DESC',
    'per_page_limit' => 25,
    'search_service' => GithubSearchService::class,
];
```
All default parameters are changeable using query param.
REST API endpoint is documented in Swagger at `/swagger.json` endpoint which is generated dynamically through annotations.

Search provider can be easily replaced using a `SearchProvider` interface.
I've used [knplabs/github-api](https://github.com/knplabs/github-api) but there is also available client for Gitlab as well.
I had to fix the library temporarily in `GithubSearchService` implementation because of non standard with GitHub v3 'page' param 
which was not handled by library properly.

## Usage

### Production

#### REST API

Run make target to build and run production ready environment
```bash
make prod
```

Then enter `http://localhost:9999/`.
There are two available endpoints:
* `/swagger.json` - with Swagger API specification in JSON
* `/api/search` - for search requests for eg. [composer.json user:symfony](http://localhost:9999/api/search?query=composer.json+user:symfony)

#### Console application

Run bash inside Docker container using `prod-bash` make target like:
```bash
make prod-bash
```

Then run console app for eg.:
```bash
bin/console search composer.json user:symfony
```
> Note! Use `--help` option to get usage description.

### Development

Run dev environment in Docker container
```bash
make dev
```

Run test using make target
```bash
make test
```

Run code sniffer fixer using make target
```bash
make cs
```

Run all using one make target
```bash
make it
```

## Future optimisations

There are few point to cover during future development:
* Prepare `php.ini` for production environment
* Add acceptance tests for REST API testing using BDD (for eg. Behat)
* Compact all Docker Compose files in make targets as variables
* Build all in one Docker container and pass artifact to production ready Docker image