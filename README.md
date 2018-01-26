# Source Code Search

Production-ready simple REST API for searching in all Github code.

## Requirements

* Must be easy to replace, so I can change provider if I some day want to use GitLab or Bitbucket, or all of them
* Must have an endpoint that accepts a query, and returns the paginated result. One hit (result) must comprise of owner name, repository name and file name
* The number of hits per page should be 25 by default, but must be changeable by a query string parameter
* The page number should be changeable by a query string parameter
* The sorting should be by score, but must be changeable by a query string parameter

## Development

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
