<?php
namespace Authentication;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    // 'controllers' => [
    //     'factories' => [
    //         Controller\AuthenticationController::class => InvokableFactory::class,
    //     ],
    // ],

     // The following section is new and should be added to your file:
     'router' => [
        'routes' => [
            'authentication' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/auth[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\AuthenticationController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    
    'view_manager' => [
        'template_path_stack' => [
            'authentication' => __DIR__ . '/../view',
        ],
    ],
];