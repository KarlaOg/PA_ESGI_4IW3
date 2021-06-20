# Annual Project ESGI

## Table of contents

- [Annual Project ESGI](#annual-project-esgi)
  - [Table of contents](#table-of-contents)
  - [General info](#general-info)
  - [Technologies](#technologies)
- [Installation](#installation)
  - [Authors](#authors)

## General info

Web Engineering 4th Year Annual Educational Project

## Technologies

Symfony 5

- PHP
- Twig
- Bootstrap
- Shell

# Installation

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

Drop DB

```
php bin/console doctrine:schema:drop --full-database --force
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

Jouer les fixtures

```
php bin/console d:f:l --no-interaction
```

Install webpack

```
yarn install
```

Pour mettre a jour le css en background

```
yarn watch
```

## Authors

- Yanis FENICHE
- Karla OG
- Coumba CAMARA
- Moussia MOTTAL
- Mohand AIT AMARA
- Emeline GARO
