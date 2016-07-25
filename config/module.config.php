<?php

return [
    'listeners'        => [
        'ZendBaseModel.exceptionListener' => 'ZendBaseModel.exception.exceptionListener'
    ],
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
            'ZendBaseModel.cache.memcached'             => \ZendBaseModel\PortAdapter\Dispatch\Zend\ServiceLocator\Cache\MemcachedFactory::class,
            'ZendBaseModel.exception.exceptionListener' => \ZendBaseModel\PortAdapter\Dispatch\Zend\ServiceLocator\Exception\ExceptionFactory::class,
        ],
    ],
    'controllers'      => [
        'invokables' => [
            'ZendBaseModel\Controller\Exception' => \ZendBaseModel\PortAdapter\Dispatch\Zend\Controller\UI\ExceptionController::class,
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
            'modalConfirmation' => \ZendBaseModel\PortAdapter\Dispatch\Zend\View\Helper\ModalConfirmation\Helper::class,
            'modalAlert'        => \ZendBaseModel\PortAdapter\Dispatch\Zend\View\Helper\ModalAlert\Helper::class,
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
