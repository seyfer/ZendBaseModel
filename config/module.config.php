<?php

return [
    'doctrine'      => [
        'driver'     => [
            'model_entries' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/ZendBaseModel/Entity']
            ],
            'orm_default'   => [
                'drivers' => [
                    'ZendBaseModel\Entity' => 'model_entries'
                ]
            ]
        ],
        'connection' => [
            'orm_default' => [
                'driverClass'  => 'BsbDoctrineReconnect\DBAL\Driver\PDOMySql\Driver',
                'wrapperClass' => 'BsbDoctrineReconnect\DBAL\Connection',
                'params'       => [
                    'driverOptions' => [
                        'x_reconnect_attempts' => 10,
                    ],
                ]
            ]
        ]
    ],
    'module_config' => [
    ],
    'router'        => [
        'routes' => [
        ],
    ],
    'console'       => [
        'router' => [
            'routes' => [
            ]
        ]
    ],
    'controllers'   => [
        'invokables' => [
        ],
    ],
    'view_manager'  => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_path_stack'      => [
            __DIR__ . '/../view',
        ],
    ],
];
