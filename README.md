# Turbo Special Octo Engine

It's a Turbo Special Octo Engine dude!

## Dependencies

* php >= 7.4;
* [composer](https://getcomposer.org/); and
* For development, [phpUnit](https://phpunit.de/).

## Run it
In order to use this you should install the dependencies using [composer](https://getcomposer.org/).

```
$ composer install
```

Once finished, you will be able to run the `cli` tool using this:

```
$ php -f cli.php
```

### Available commands
To pass parameters to it, opt for this command syntax:

#### Displaying Help information
```
$ php -f cli.php -- --help
```
#### Displaying Usage information
```
$ php -f cli.php -- --help
```
#### Search for bracket texts inside a file
```
$ php -f cli.php -- --file fixtures/lorem.txt
```

## Tests

You could test the `src/Cli.php` class using [phpUnit](https://phpunit.de/).

Run it with:

```
$ vendor/bin/phpunit
```

Or with debug enabled
```
$ composer phpunit
```