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
                'modules-list' => array(
                    'options' => array(
                        'route'    => 'modules list',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Modules\Controller\Console',
                            'controller' => 'List',
                            'action'     => 'show'
                        )
                    )
                )
            )
        )
    ),
);
