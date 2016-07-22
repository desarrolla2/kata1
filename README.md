# KATA 1

A code kata is an exercise in programming which helps a programmer hone their skills through practice and repetition. The term was probably first coined by Dave Thomas, co-author of the book The Pragmatic Programmer, in a bow to the Japanese concept of kata in the martial arts.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/161c9238-ff2e-410a-aeff-7de8fbb23518/big.png)](https://insight.sensiolabs.com/projects/161c9238-ff2e-410a-aeff-7de8fbb23518)

[![Build Status](https://travis-ci.org/desarrolla2/kata1.svg?branch=master)](https://travis-ci.org/desarrolla2/kata1)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/desarrolla2/kata1/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/desarrolla2/kata1/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/desarrolla2/kata1/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/desarrolla2/kata1/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/desarrolla2/kata1/badges/build.png?b=master)](https://scrutinizer-ci.com/g/desarrolla2/kata1/build-status/master)

## Installation

> **Requires PHP 5.6 or newer.**

You need [Composer](http://getcomposer.org/) and [Bower](https://bower.io/) to run this project.

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

or you can view travis-ci results [here](https://travis-ci.org/desarrolla2/kata1)

## Coding Style

We use Symfony2 [Coding Standards](http://symfony.com/doc/current/contributing/code/standards.html) you can fix your code running before send your PR 

```bash
ant cs
```

This step requiere [ant](https://ant.apache.org/) and [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) installed in your system

You can see scrutinizer code rating [here](https://scrutinizer-ci.com/g/desarrolla2/kata1/)


## License

This project is licensed under the MIT license.
