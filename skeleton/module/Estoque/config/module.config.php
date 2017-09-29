<?php

// namespace Estoque;

return array(
    'router' => array(
        'routes' => array(
            'application' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/[:controller[/:action]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Estoque\Controller',
                        'controller' => 'Index',
                        'action' => 'Index'
                    )
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Estoque\Controller\Index' => 'Estoque\Controller\IndexController'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__.'/../view/',
        ),
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml'
        )
    ),
    'doctrine' => array(
          'driver' => array(
            'application_entities' => array(
              'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
              'cache' => 'array',
              'paths' => array(__DIR__ . '/../src/Estoque/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Estoque\Entity' => 'application_entities'
                ),
            ),
        ),
    ),
);