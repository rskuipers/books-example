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

### Tasks

1. Add ISBN to Book
2. Refactor author to separate entity
3. Refactor `\App\Entity\Book::timeSinceRelease`
4. Consider other quality improvements
