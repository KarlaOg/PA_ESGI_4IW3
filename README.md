# Annual Project ESGI

## Table of contents

- [Annual Project ESGI](#annual-project-esgi)
  - [Table of contents](#table-of-contents)
  - [General info](#general-info)
  - [Technologies](#technologies)
  - [Setup](#setup)
- [Load fixtures](#load-fixtures)
- [Helper](#helper)
  - [Authors](#authors)

## General info

Web Engineering 4th Year Annual Educational Project

## Technologies

Symfony 5

- PHP
- Twig
- Bootstrap
- Shell

## Setup

To run this project, install it locally using:

```
$ composer install
$ php bin/console doctrine:database:create
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate

$ php bin/console doctrine:fixtures:load
$ php bin/phpunit
```

# Load fixtures

```
 docker-compose exec php bin/console d:f:l --no-interactionrea
```

# Helper

if errors with node-sass (mac)
run

```
yarn encore dev
```

## Authors

- Yanis FENICHE
- Karla OG
- Coumba CAMARA
- Moussia MOTTAL
- Mohand AIT AMARA
- Emeline GARO
