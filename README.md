# My Symfony 7.3 Template

## Overview

### Goals

This project is a starter for a Symfony 7.3 project with preinstalled / preconfigured docker environment, testing and quality tools. 

### Features (for now...)

- Preconfigured phpunit with standard values and Testcontainers
- Preconfigured PHPStan with standard values
- Preconfigured PHP CS Fixer with default Symfony ruleset


### Docker for dev

You can use the docker/dev/docker-compose.yml to provide
- a Caddy / FrankenPHP server (with XDebug)
- a PostgreSQL server
- a Redis cache server

## Testing

### Backend

Your can run tests (unit and integration) in your docker dev container :

To execute tests with code coverage and HTML report :  
  `XDEBUG_MODE=coverage vendor/bin/phpunit`

To execute tests without coverage :
 `vendor/bin/phpunit --no-coverage`

There are 2 tests suites by default : 'Unit' and 'Integration'. You can use the `--testsuites` command line option to select one.

Important : all your integration tests (extending KernelTestCase, WebTestCase...) filenames MUST end with 'IT.php' to be detected.

TestContainers are only started in integration testing ; unit tests by definition don't need them and run much faster.
By defaults, in integration testing, 2 test containers are created : 

- PostgreSQL 16 -  fixtures for the database are loaded by launching commands for schema creation then fixtures with Doctrine Fixtures
- Redis 7 

You may also execute tests in your IDE but this requires a bit of configuration (at least in PHPStorm) to use the container's PHP as interpreter, set up the directories aliases, use the appropriate phpunit.xml... 

## Quality

### Backend

Run PHPStan in your docker container.

`vendor/bin/phpstan`