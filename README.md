# Annual Project ESGI

## Table of contents

- [Annual Project ESGI](#annual-project-esgi)
  - [Table of contents](#table-of-contents)
  - [General info](#general-info)
  - [Technologies](#technologies)
  - [Setup](#setup)
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

## Setup

- docker-compose build --no-cache
- docker-compose up -d

Pour Windows :
```
$ cd docker/nginx/
$ find . -name "*.sh" | xargs dos2unix
```

##### Debug docker

- docker-compose ps
- docker-compose logs -f [CONTAINER(php|node|nginx|db)]



##### Maker
```
docker-compose exec php bin/console make:controller
docker-compose exec php bin/console make:entity
docker-compose exec php bin/console make:form
docker-compose exec php bin/console make:crud
```

## Doctrine
##### Mise à jour de votre BDD avec Doctrine:Schema:Update
```
// Voir les réquètes préparées
docker-compose exec php bin/console doctrine:schema:update --dump-sql
// Jouer les requètes préparées
docker-compose exec php bin/console doctrine:schema:update --force
```
##### Relation
https://symfony.com/doc/current/doctrine/associations.html

##### Custom query avec DQL (repository)
https://symfony.com/doc/current/doctrine.html#querying-with-the-query-builder
https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/que$


##### MAJ BDD avec les migrations
https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html
```
docker-compose exec php bin/console make:migration
```

## DataFixtures
##### Installation et utilisation
https://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html
```
composer require --dev doctrine/doctrine-fixtures-bundle
docker-compose exec php bin/console make:fixtures
php bin/console doctrine:fixtures:load
```

## Authors

- Yanis FENICHE
- Karla OG
- Coumba CAMARA
- Moussia MOTTAL
- Mohand AIT AMARA
- Emeline GARO
