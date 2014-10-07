Modules
==============

ZF2 Module for managing module migrations

Introduction
------------
Each module must contain own migrations, version and settings.

Requirements
------------
* [Zend Framework 2](https://github.com/zendframework/zf2) (latest master)
* [CLImate](https://github.com/thephpleague/climate)

Features / Goals
----------------
* List used modules/libraries/dependencies in console [DONE]
* Install new modules (if it have initial migrations) in console [IN PROGRESS]
* Upgrade modules (if it have migrations) in console [IN PROGRESS]
* List used modules in admin (backend) zone [IN PROGRESS]
* Install new modules (if it have initial migrations) in admin (backend) zone [IN PROGRESS]
* Upgrade modules (if it have migrations) in admin (backend) zone [IN PROGRESS]

Installation
------------
### Main Setup

#### By cloning project

1. Clone this project into your `./vendor/` directory.

#### With composer

1. Add this project in your composer.json:

    ```json
    "require": {
        "t4web/modules": "0.2.*"
    }
    ```

2. Now tell composer to download Modules by running the command:

    ```bash
    $ php composer.phar update
    ```

#### Post installation

Enabling it in your `application.config.php`file.

```php
<?php
return array(
    'modules' => array(
        // ...
        'Modules',
    ),
    // ...
);
```

Usage
------------
For manage modules migrations each module must have config/migrations.config.php
```php
return array(
    'unknown' => 'Authentication\Migrations\Install',
    '0.2.1' => 'Authentication\Migrations\Upgrade_0_2_1',
    '0.2.2' => 'Authentication\Migrations\Upgrade_0_2_2',
    '1.0.0' => 'Authentication\Migrations\Upgrade_1_0_0',
);
```
'unknown' - runs for modules, wich have initial migrations, value - migration class. '0.2.1', '1.0.0' - version number for start upgrades (run migrations), value - migration class

When perform migration version will execute consecutively.

Example 1: Your module version is '0.2.1', new version is '1.0.1' when you perform
migrations, will be runs '0.2.1', '0.2.2', '1.0.0'.

Example 2: Your module version is '0.2.2', new version is '1.0.1' when you perform
migrations, will be runs '0.2.2', '1.0.0'.

Example 3: Your module version is '0.2.13', new version is '1.0.1' when you perform
migrations, will be runs '1.0.0'.

For list modules run
```bash
$ php public/index.php modules list
```
result will be like this:
<p align="center"><img src="http://t4web.com.ua/var/module-list-example.png" width="844" alt="module list example" /></p>

Testing
------------
Unit test runnig from authentication module directory.

```bash
$ cd vendor/t4web/modules/tests
$ phpunit
```

For running only Functional tests you need run phpunit, like this:

```bash
$ phpunit --filter Functional
```

For running only Unit tests you need run phpunit, like this:

```bash
$ phpunit --filter Unit
```
