# My Symfony 7.3 base project

[![Backend CI](https://img.shields.io/github/actions/workflow/status/wilzwert/symfony-project-starter/ci_symfony.yml?label=Symfony%20CI&logo=Github)](https://github.com/wilzwert/symfony-project-starter/actions/workflows/ci_symfony.yml)

[![Backend coverage](https://img.shields.io/codecov/c/github/wilzwert/symfony-project-starter?flag=symfony&label=Symfony%20coverage&logo=JUnit5)](https://wilzwert.github.io/symfony-project-starter/coverage-symfony/)
[![Backend Quality Gate Status](https://img.shields.io/sonar/quality_gate/symfony-project-starter-symfony?server=https%3A%2F%2Fsonarcloud.io&logo=sonarcloud&label=Symfony%20quality%20gate)](https://sonarcloud.io/summary/new_code?id=symfony-project-starter-symfony)

[Symfony coverage report](https://wilzwert.github.io/symfony-project-starter/coverage-symfony/)


## Table of contents

- [Overview](#overview)
  - [Goals](#goals)
  - [Features](#features)
- [Usage](#usage)
  - [Requirements](#requirements)
  - [Docker](#docker)
  - [Symfony](#symfony)
- [Local testing / quality analysis](#local-testing--quality-analysis)
    - [Symfony project](#symfony-project)
    - [PHPSTan](#phpstan)
    - [PHP CS Fixer](#php-cs-fixer)
- [Symfony CI](#symfony-ci)    

## Overview

### Goals

This is a dummy Symfony 7.3 project with a full docker dev environment, testing and quality tools.
If it can be of use to you as a project starter I'll be glad. 
The symfony project resides in the symfony directory to allow to e.g. add a separate frontend in its own directory. 

However, there are probably better ways to set up this kind of project so any feedback is appreciated. I'm also open to suggestions such as DDev which I haven't tried yet.

### Features

- Full Docker dev environment, tested on Windows 11 (WSL 2) host and Linux (Pop!_OS) : FrankenPHP, PostgreSQL, Redis
- Preconfigured PHPUnit with standard values and Testcontainers
- dama/doctrine-test-bundle used to wrap integration tests in transactions ; this is very useful to keep tests predictible and consistent, even when writing in the database
- Preconfigured PHPStan with standard values
- Preconfigured PHP CS Fixer with default Symfony ruleset
- Preconfigured PHPUnit coverage with html and xml (clover) output
- CI : tests with coverage, Sonar scan + quality gate, coverage report in GitHub Pages, Codecov

## Usage

### Requirements

Your host system must have a usable Docker environment : 

- Windows host : Docker Desktop (WSL 2)
- Linux : docker, docker compose

I'm not sure what versions are needed but this project has been tested with Docker Desktop >= 4.49 (Windows) and docker >= 28.5 in Pop!_OS.

If you wish to use this project as a "Symfony starter", you also have some configuration to do.

### Docker

You can use the docker/dev/docker-compose.yml to provide
- a Caddy / FrankenPHP server (with XDebug)
- a PostgreSQL server
- a Redis cache server

The environment should be usable as is, but you can (should) configure some variables.  
In docker/dev, copy .env.dist to .env and adapt to your configuration.

The [docker/dev/docker-compose.yml](docker/dev/docker-compose.yml) is designed to be portable, and does a lot of mounting to be able to work 
on a Windows host without compromising perfs. If you're on a Linux host, you can mount the whole app as a single volume 
`../../symfony:/app`

Build from project root with `docker compose -f docker/dev/docker-compose.yml build`, 
run with `docker compose -f docker/dev/docker-compose.yml up -d` 

### Symfony
Copy all .env.*.dist to .env.* and adapt to your configuration. You should set up an APP_SECRET, and may want 
to change the DATABASE_URL and REDIS_URL in .env.dev if you changed the docker/dev/.env variables.

As for testing, you may copy the PHPUnit.dist.xml to PHPUnit.xml and configure appropriately, 
although PHPUnit.dist.xml should also work without specific configuration. 
You don't have to modify .env.test by default because Testcontainers automatically sets up ephemeral PostgreSQL and 
Redis containers.

Base dependencies are installed by building the Docker image.
When new dependencies are needed, run usual commands in your docker container :
`composer require package/name`, `composer require --dev package/name`, `composer update` and so on.


### IDE

One of the benefits of having a distributable docker environment is to provide PHP tooling without installing it locally on your host.

I personally use PHPStorm, which allows to (among lots of other stuff) : 
- use the PHP available in your container as a CLI Interpreter
- configure PHPStorm for debug sessions with XDebug 
- configure different test run configurations for PHPUnit
- run tests both in the container and PHPStorm
- use the container's composer, which is useful if your hosts runs on Windows and you have to manually "install" the vendor directory which is not 
mounted ny default due to performances issues. That way you can benefit from PHPStorm's completion, code browsing...

## Local testing / quality analysis

### Symfony project

Tests can be run (unit and integration) in the docker dev container :

To execute tests with code coverage check and report :
    
`composer test`

By default, code coverage below 80% will fail.


To execute tests without coverage :

`vendor/bin/PHPUnit --no-coverage`

There are 2 tests suites by default : 'Unit' and 'Integration'. You can use the `--testsuites` command line option to select one.

Important : all integration tests (extending KernelTestCase, WebTestCase...) filenames MUST end with 'IT.php' to be detected. 
This is a personal convention ; it can be changed in the `isIntegrationTest` method [symfony/tests/bootstrap/TestSuiteService.php](symfony/tests/bootstrap/TestSuiteService.php).

TestContainers are only started in integration testing ; unit tests by definition don't need them and run much faster.
By default, in integration testing, 2 test containers are created : 

- PostgreSQL 16 -  fixtures for the database are loaded by launching commands for schema creation then fixtures with Doctrine Fixtures
- Redis 7

This is done by subscribing to PHPUnit events (see subscribers and test container handlers in [symfony/tests/bootstrap](symfony/tests/bootstrap)).

### PHPStan

Run PHPStan in your docker app container.

`vendor/bin/phpstan`

### PHP CS Fixer

Run PHP CS Fixer in your docker app container, e.g:

`vendor/bin/php-cs-fixer fix`

## Symfony CI

See [.github/workflows/ci_symfony.yml](.github/workflows/ci_symfony.yml) for CI.

At the moment it is designed to : 
- run tests with coverage
- run PHPStan analysis and upload its report as an artefact (non-blocking in case of errors)
- run Sonar scan and check quality gate (failure to pass the quality gate fails the workflow)
- upload html coverage report to GitHub page (only on push on master branch)
- upload coverage report to Codecov (only on push on master branch)