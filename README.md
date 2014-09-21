Modules
==============

ZF2 Module for managing module migrations

Introduction
------------
Each module must contain own migrations, version and settings.

Requirements
------------
* [Zend Framework 2](https://github.com/zendframework/zf2) (latest master)

Features / Goals
----------------
* List used modules in admin (backend) zone [IN PROGRESS]
* List used modules in console [IN PROGRESS]
* Install new modules (if it have initial migrations) in admin (backend) zone [IN PROGRESS]
* Install new modules (if it have initial migrations) in console [IN PROGRESS]
* Upgrade modules (if it have migrations) in admin (backend) zone [IN PROGRESS]
* Upgrade modules (if it have migrations) in console [IN PROGRESS]

Installation
------------
### Main Setup

#### By cloning project

1. Clone this project into your `./vendor/` directory.

#### With composer

1. Add this project in your composer.json:

    ```json
    "require": {
        "t4web/modules": "dev-master"
    }
    ```

2. Now tell composer to download Modules by running the command:

    ```bash
    $ php composer.phar update
    ```

#### Post installation

1. Enabling it in your `application.config.php`file.

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
Testing
------------
Unit test runnig from authentication module directory.
    ```bash
    $ cd vendor/t4web/modules/tests
    $ phupnit
    ```
For running only Functional tests you need run phpunit, like this:
    ```bash
    $ phupnit --filter Functional
    ```
For running only Unit tests you need run phpunit, like this:
    ```bash
    $ phupnit --filter Unit
    ```
