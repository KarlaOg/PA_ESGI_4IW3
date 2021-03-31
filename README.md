# Annual Project ESGI

## Table of contents

- [Annual Project ESGI](#annual-project-esgi)
  - [Table of contents](#table-of-contents)
  - [General info](#general-info)
  - [Technologies](#technologies)
- [Lancement de l'app SANS DOCKER](#lancement-de-lapp-sans-docker)
  - [/!\ Attention tout se passe dans le dossier /app](#-attention-tout-se-passe-dans-le-dossier-app)
  - [Installation avec docker](#installation-avec-docker)
  - [Load fixtures](#load-fixtures)
  - [Authors](#authors)

## General info

Web Engineering 4th Year Annual Educational Project

## Technologies

Symfony 5

- PHP
- Twig
- Bootstrap
- Shell

# Lancement de l'app SANS DOCKER

## /!\ Attention tout se passe dans le dossier /app

Installation de composer

```
composer install
```

Créer un fichier ou edit le fichier **.env.local**

```
DATABASE_URL=mysql://user:password@127.0.0.1:3306/pa-sf?serverVersion=5.7
```

Change user et password par votre mot de passe et user de phpmyadmin

(de base ca doit êre root et root sous MAC)

Créer la db

```
php bin/console doctrine:database:create
```

Lancer le server sf

```
symfony serve
```

Lancer les migrations

```
php bin/console make:migration
php bin/console doctrine:migrations:migrate --no-interaction
```

Install webpack

```
yarn install
```

## Installation avec docker

To run this project, install it locally using:

```
$ composer install
$ php bin/console doctrine:database:create
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate

$ php bin/console doctrine:fixtures:load
$ php bin/phpunit
```

## Load fixtures

```
docker-compose exec php bin/console d:f:l --no-interaction
```

## Authors

- Yanis FENICHE
- Karla OG
- Coumba CAMARA
- Moussia MOTTAL
- Mohand AIT AMARA
- Emeline GARO
