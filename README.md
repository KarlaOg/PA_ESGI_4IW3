# Annual Project ESGI

## Table of contents
* [General info](#general-info)
* [Technologies](#technologies)
* [Setup](#setup)
* [Authors](#authors)

## General info
Web Engineering 4th Year Annual Educational Project

## Technologies
Symfony 5
* PHP
* Twig
* Bootstrap
* Shell
	
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

## Authors
* Yanis FENICHE
* Karla OG
* Coumba CAMARA 
* Moussia MOTTAL
* Mohand AIT AMARA
* Emeline GARO



