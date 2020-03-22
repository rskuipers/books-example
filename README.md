## Books

### Setup

Make sure you have docker installed on your computer.

```shell script
$ docker-compose up
```

Your application is now live at http://localhost:23080/

### Tests

Run tests using PHPUnit.

```shell script
$ docker-compose exec php vendor/bin/phpunit
```

### Debugging

You can enable XDebug with the following steps:

1. `$ cp docker-compose.override.yml.dist docker-compose.override.yml`
2. Change `ENABLE_XDEBUG` to `"true"`
3. `docker-compose up --build`

### Tasks

1. Add ISBN to Book
2. Refactor author to separate entity
3. Refactor `\App\Entity\Book::timeSinceRelease`
4. Consider other quality improvements
