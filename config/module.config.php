<?php

return array(

    'view_manager' => array(
        'template_path_stack' => array(
            'modules' => __DIR__ . '/../view',
        ),
    ),

    'console' => array(
        'router' => array(
            'routes' => array(
                'modules-init' => array(
                    'options' => array(
                        'route'    => 'modules init',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Modules\Controller\Console',
                            'controller' => 'Init',
                            'action'     => 'run'
                        )
                    )
                ),
                'modules-list' => array(
                    'options' => array(
                        'route'    => 'modules list',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Modules\Controller\Console',
                            'controller' => 'List',
                            'action'     => 'show'
                        )
                    )
                ),
                'modules-install' => array(
                    'options' => array(
                        'route'    => 'modules install <moduleName>',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Modules\Controller\Console',
                            'controller' => 'Install',
                            'action'     => 'run'
                        )
                    )
                )
            )
        )
    ),
);
