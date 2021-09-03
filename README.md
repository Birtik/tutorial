 # TutorialShop Project

## First Steps
Setup and start application in dev mode
```shell script
docker-compose build
```

```shell script
docker-compose run --rm app composer install
docker-compose up
```

## Schema
```shell script
docker-compose exec app php bin/console d:s:u --force
```

## Fixtures
```shell script
docker-compose exec app php bin/console d:f:l
```

## Composer
```shell script
docker-compose exec app composer some_awesome_command
```

## Access service
Api
```shell script
open http://0.0.0.0/api/doc
```

PHPAdmin
```shell script
open http://$(docker-compose port pma 8080)
```

Mailhog
```shell script
open http://$(docker-compose port mailhog 8025)
```