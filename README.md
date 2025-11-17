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

If you wish to use this project as a "Symfony starter", you have some configuration to do.

### Docker

You can use the docker/dev/docker-compose.yml to provide
- a Caddy / FrankenPHP server (with XDebug)
- a PostgreSQL server
- a Redis cache server

The environment should be usable as is, but you can (should) configure some variables.  
In docker/dev, copy .env.dist to .env and adapt to your configuration.

The docker/dev/docker-compose.yml is designed to be portable, and does a lot of mounting to be able to work 
on a Windows host without compromising perfs. If you're on a Linux host, you can mount the whole app as a single volume 
`../../symfony:/app`

### Symfony
Copy all .env.*.dist to .env.* and adapt to your configuration. You should set up an APP_SECRET, and may want 
to change the DATABASE_URL and REDIS_URL in .env.dev if you changed the docker/dev/.env variables.

As for testing, you may copy the PHPUnit.dist.xml to PHPUnit.xml and configure appropriately, 
although PHPUnit.dist.xml should also work without specific configurations. 
You don't have to modify .env.test by default because Testcontainers automatically sets up ephemeral PostgreSQL and Redis containers. 


## Local testing / quality analysis

### Symfony project

Tests can be run (unit and integration) in the docker dev container :

To execute tests with code coverage and HTML report :  
  `XDEBUG_MODE=coverage vendor/bin/PHPUnit`

To execute tests without coverage :
 `vendor/bin/PHPUnit --no-coverage`

There are 2 tests suites by default : 'Unit' and 'Integration'. You can use the `--testsuites` command line option to select one.

Important : all integration tests (extending KernelTestCase, WebTestCase...) filenames MUST end with 'IT.php' to be detected.

TestContainers are only started in integration testing ; unit tests by definition don't need them and run much faster.
By default, in integration testing, 2 test containers are created : 

- PostgreSQL 16 -  fixtures for the database are loaded by launching commands for schema creation then fixtures with Doctrine Fixtures
- Redis 7

You may also execute tests in your IDE but this requires a bit of configuration (at least in PHPStorm) to use the container's PHP as interpreter, set up the directories aliases, use the appropriate PHPUnit.xml...

### PHPStan

Run PHPStan in your docker app container.

`vendor/bin/phpstan`

### PHP CS Fixer

Run PHP CS Fixer in your docker app container.

`vendor/bin/php-cs-fixer`

## Symfony CI

See ./github/workflows/ci_symfony.yml for CI.

At the moment it is designed to : 
- run tests with coverage
- run PHPStan analysis and upload its report as an artefact (non-blocking in case of errors)
- run Sonar scan and check quality gate (failure to pass the quality gate fails the workflow)
- upload html coverage report to GitHub page (only on push on master branch)
- upload coverage report to Codecov (only on push on master branch)