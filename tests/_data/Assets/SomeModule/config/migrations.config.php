<?php

return [
    'unknown' => [
        'run'  => 'SomeModule\Migrations\Migration_0_0_1',
        'next' => '0.0.2'
    ],
    '0.0.2'   => [
        'run'  => 'SomeModule\Migrations\Migration_0_0_2',
        'next' => '0.1.0'
    ],
    '0.1.0'   => [
        'run'  => 'SomeModule\Migrations\Migration_0_1_0',
        'next' => '0.1.3'
    ],
    '0.1.3'   => [
        'run'  => 'SomeModule\Migrations\Migration_0_1_3',
        'next' => '0.1.4'
    ],
    '0.1.4'   => [
        'current' => true
    ],
];
