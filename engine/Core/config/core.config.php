<?php
//'config_static_paths' => ['./vendor/seyfer/zend-base-model/engine/Core/config/core.config.php']
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
            'controller' => 'Core\Controller\Exception',
            'action'     => 'index'
        ]
    ],
    'listeners'        => [
        'core.exception.exceptionListener' => 'core.exception.exceptionListener'
    ],
    'service_manager'  => [
        'factories'          => [
            'core.cache.memcached'                => Core\Cache\MemcachedFactory::class,
            'core.tool.compositeschema'           => Core\Tool\CompositeSchemaFactory::class,
            'core.crypt.simple'                   => Core\Crypt\SimpleFactory::class,
            'core.exception.exceptionListener'    => Core\Exception\ExceptionFactory::class,
            'core.queue.queueService'             => Core\Queue\QueueFactory::class,
            'core.mandrill.mandrillSenderService' => Core\Mandrill\Factory::class,
        ],
        'abstract_factories' => [
            'Zend\\Log\\LoggerAbstractServiceFactory',
        ]
    ],
    'controllers'      => [
        'invokables' => [
            'Core\Controller\Schema'    => Core\Controller\SchemaController::class,
            'Core\Controller\Env'       => Core\Controller\EnvController::class,
            'Core\Controller\Exception' => Core\Controller\ExceptionController::class,
        ]
    ],
    'console'          => [
        'router' => [
            'routes' => [
                'schema' => [
                    'type'    => 'simple',
                    'options' => [
                        'route'    => 'schema generateDiff [--withDrop=<withDrop>] '
                                      . '[--withKey=<withKey>]',
                        'defaults' => [
                            'controller' => 'Core\Controller\Schema',
                            'action'     => 'generateDiff',
                        ]
                    ]
                ],
                'env'    => [
                    'type'    => 'simple',
                    'options' => [
                        'route'    => 'env install <env> [--dbu=<dbu>] [--dbp=<dbp>] [--dbn=<dbn>]',
                        'defaults' => [
                            'controller' => 'Core\Controller\Env',
                            'action'     => 'install',
                        ]
                    ]
                ],
            ]
        ]
    ],
    'log'              => [
        'logger.exceptions' => [
            'writers' => [
                [
                    'name'    => 'Stream',
                    'options' => [
                        'stream'        => APPLICATION_DIR . '/data/logs/exception.log',
                        'log_separator' => PHP_EOL . PHP_EOL
                    ]
                ]
            ]
        ],
    ],
    'view_manager'     => [
        'doctype'             => 'HTML5',
        'strategies'          => [
            'ViewJsonStrategy'
        ],
        'template_path_stack' => [
            APPLICATION_DIR . "/view/"
        ],
    ],
    'view_helpers'     => [
        'invokables' => [
            'modalConfirmation' => 'Core\View\Helper\ModalConfirmation\Helper',
            'modalAlert'        => 'Core\View\Helper\ModalAlert\Helper',
        ],
    ],
    'mandrill'         => [
        'apiKey'  => "DoblKuBv5LXaVlNqzlCnQQ",
        'replyTo' => "no-reply@panel100.com",
        'from'    => "no-reply@panel100.com"
    ],
    'email'            => [
        'from' => 'no-reply@panel100.com'
    ],
];