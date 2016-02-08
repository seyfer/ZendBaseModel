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
//        ],
//        'cache' => [
//            'memcached' => [
//                'instance' => 'core.cache.memcached',
//            ],
//        ],
//    ],
return [
    'exceptionHandler' => [
        'routeMatch'      => [
            'controller' => 'ZendBaseModel\Controller\Exception',
            'action'     => 'index'
        ],
        //set this to false to allow other handlers handle exception
        'stopPropagation' => true,
    ],
    'service_manager'  => [
        'factories' => [
            'ZendBaseModel.cache.memcached'             => ZendBaseModel\Cache\MemcachedFactory::class,
            'ZendBaseModel.exception.exceptionListener' => ZendBaseModel\Exception\ExceptionFactory::class,
        ],
    ],
    'controllers'      => [
        'invokables' => [
            'ZendBaseModel\Controller\Exception' => ZendBaseModel\Controller\ExceptionController::class,
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
    'view_manager'     => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_path_stack'      => [
            __DIR__ . '/../view',
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
];
