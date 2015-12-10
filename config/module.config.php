<?php

//    'doctrine'      => [
//        'driver'     => [
//            'model_entries' => [
//                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
//                'cache' => 'array',
//                'paths' => [__DIR__ . '/../src/ZendBaseModel/Entity']
//            ],
//            'orm_default'   => [
//                'drivers' => [
//                    'ZendBaseModel\Entity' => 'model_entries'
//                ]
//            ]
//        ],
//        'connection' => [
//            'orm_default' => [
//                'driverClass'  => 'BsbDoctrineReconnect\DBAL\Driver\PDOMySql\Driver',
//                'wrapperClass' => 'BsbDoctrineReconnect\DBAL\Connection',
//                'params'       => [
//                    'driverOptions' => [
//                        'x_reconnect_attempts' => 10,
//                    ],
//                ]
//            ]
//        ]
//    ],
return [
    'doctrine'         => [
        'cache' => [
            'memcached' => [
                'instance' => 'core.cache.memcached',
            ],
        ],
    ],
    'exceptionHandler' => [
        'routeMatch' => [
            'controller' => 'ZendBaseModel\Controller\Exception',
            'action'     => 'index'
        ]
    ],
    'listeners'        => [
        'core.exception.exceptionListener' => 'core.exception.exceptionListener'
    ],
    'service_manager'  => [
        'factories' => [
            'core.cache.memcached'             => ZendBaseModel\Cache\MemcachedFactory::class,
            'core.exception.exceptionListener' => Core\Exception\ExceptionFactory::class,
        ],
    ],
    'controllers'      => [
        'invokables' => [
            'Core\Controller\Exception' => Core\Controller\ExceptionController::class,
        ]
    ],
    'log'              => [
        'logger.exceptions' => [
            'writers' => [
                [
                    'name'    => 'Stream',
                    'options' => [
                        'stream'        => 'data/logs/exception.log',
                        'log_separator' => PHP_EOL . PHP_EOL
                    ]
                ]
            ]
        ],
    ],
    'view_helpers'     => [
        'invokables' => [
            'modalConfirmation' => 'ZendBaseModel\View\Helper\ModalConfirmation\Helper',
            'modalAlert'        => 'ZendBaseModel\View\Helper\ModalAlert\Helper',
        ],
    ],
    'module_config'    => [
    ],
    'router'           => [
        'routes' => [
        ],
    ],
    'console'          => [
        'router' => [
            'routes' => [
            ]
        ]
    ],
    'controllers'      => [
        'invokables' => [
        ],
    ],
    'view_manager'     => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_path_stack'      => [
            __DIR__ . '/../view',
        ],
    ],
];
