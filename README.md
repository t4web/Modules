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
<p align="center"><img src="http://t4web.com.ua/var/module-list-example-0.2.6.png" width="850" alt="module list example" /></p>

Statuses and actions matrix:
`+` - exists/have
`-` - not exists/not have
`COM` - in composer.lock
`DB` - in database
`ZF` - in application config
`NV` - have new version
`UP` - have migration scripts (upgrades)
`UPD DB` - action, need update database
`RUN MIG` - action, need run migration script
`Y` -Yes
`N` - No
`X` - imposible state, throw exception
`R` - need remove

No. | COM | DB | ZF | NV | UP | UPD DB | RUN MIG
 --- | --- | --- | --- | --- | --- | --- | ---
0 | + | + | + | + | + | Y | Y 
1 | + | + | + | + | - | Y | N
2 | + | + | + | - | + | X | X
3 | + | + | + | - | - | N | N
4 | + | + | - | + | + | R | N
5 | + | + | - | + | - | R | N
6 | + | + | - | - | + | R | N
7 | + | + | - | - | - | R | N
8 | + | - | + | + | + | Y | Y
9 | + | - | + | + | - | Y | N
10 | + | - | + | - | + | Y | Y
11 | + | - | + | - | - | Y | N
12 | + | - | - | + | + | N | N
13 | + | - | - | + | - | N | N
14 | + | - | - | - | + | N | N
15 | + | - | - | - | - | N | N
**No.** | **COM** | **DB** | **ZF** | **NV** | **UP** | **UPD DB** | **RUN MIG**
16 | - | + | + | + | + | Y | Y
17 | - | + | + | + | - | Y | N
18 | - | + | + | - | + | X | X
19 | - | + | + | - | - | N | N
20 | - | + | - | + | + | R | N
21 | - | + | - | + | - | R | N
22 | - | + | - | - | + | R | N
23 | - | + | - | - | - | R | N
24 | - | - | + | + | + | Y | Y
25 | - | - | + | + | - | Y | N
26 | - | - | + | - | + | X | X
27 | - | - | + | - | - | N | N
28 | - | - | - | + | + | X | X
29 | - | - | - | + | - | X | X
30 | - | - | - | - | + | X | X
31 | - | - | - | - | - | X | X

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
