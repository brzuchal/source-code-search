# Source Code Search

Production-ready simple REST API for searching in all Github code.

[![Build Status](https://api.travis-ci.org/brzuchal/source-code-search.svg?branch=master)](https://travis-ci.org/brzuchal/source-code-search)

## Requirements

* Must be easy to replace, so I can change provider if I some day want to use GitLab or Bitbucket, or all of them
* Must have an endpoint that accepts a query, and returns the paginated result. One hit (result) must comprise of owner name, repository name and file name
* The number of hits per page should be 25 by default, but must be changeable by a query string parameter
* The page number should be changeable by a query string parameter
* The sorting should be by score, but must be changeable by a query string parameter

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
* `/api/search` - for search requests

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
