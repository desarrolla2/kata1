# KATA 1

A code kata is an exercise in programming which helps a programmer hone their skills through practice and repetition. The term was probably first coined by Dave Thomas, co-author of the book The Pragmatic Programmer, in a bow to the Japanese concept of kata in the martial arts.

## Installation

Requires PHP 5.4 or newer.

You need install [Composer](http://getcomposer.org/) and [Bower](https://bower.io/)

First install dependencies

```bash
$ composer install
```

This step is not mandatory, but this will install some assets.

```bash
$ bower install
```

And run PHP Build-in

```bash
$ cd web && php -S localhost:8001
```

## Tests

Execute test suite running

```bash
vendor/bin/phpunit -c phpunit.xml.dist
```

## Coding Style

We use Symfony2 (Coding Standards)[http://symfony.com/doc/current/contributing/code/standards.html] you can fix your code running before send your PR 

```bash
ant cs
```

This step requiere [ant](https://ant.apache.org/) in your system


## License

This project is licensed under the MIT license.