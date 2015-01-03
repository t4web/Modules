 Modules
==============

ZF2 Module for list used\installed modules

Introduction
------------
Get information from composer.lock file and display

Requirements
------------
* [Zend Framework 2](https://github.com/zendframework/zf2) (latest master)
* [CLImate](https://github.com/thephpleague/climate)
* [Composer lock parser](https://github.com/t4web/ComposerLockParser)

Features / Goals
----------------
* List used modules/libraries/dependencies in console [DONE]
* List used modules in admin (backend) zone [IN PROGRESS]

Installation
------------
### Main Setup

#### By cloning project

Clone this project into your `./vendor/` directory.

#### With composer

Add this project in your composer.json:

```json
"require": {
    "t4web/modules": "0.3.*"
}
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

#### Initialize
```bash
$ php public/index.php modules init
```

Usage
------------

For list modules run
```bash
$ php public/index.php modules list
```
result will be like this:
<p align="center"><img src="http://t4web.com.ua/var/module-list-example-0.3.0.png" width="894" alt="module list example" /></p>

Testing
------------
For running tests you need install and intialize codeception, after this create/update codeception.yml in you project root and add Modules tests, like this:
```yml
include:
    - vendor/t4web/modules  # <- add modules tests to include

paths:
    log: tests/_output

settings:
    colors: true
    memory_limit: 1024M
```
After this you may run functional tests from your project root
```bash
$ codeception run
```
