<?php
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
        'factories' => [
            'core.cache.memcached'             => Core\Cache\MemcachedFactory::class,
            'core.crypt.simple'                => Core\Crypt\SimpleFactory::class,
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
                        'stream'        => APPLICATION_DIR . '/data/logs/exception.log',
                        'log_separator' => PHP_EOL . PHP_EOL
                    ]
                ]
            ]
        ],
    ],
    'view_helpers'     => [
        'invokables' => [
            'modalConfirmation' => 'Core\View\Helper\ModalConfirmation\Helper',
            'modalAlert'        => 'Core\View\Helper\ModalAlert\Helper',
        ],
    ],
];