<?php

return [
    'singular' => 'Methodology',
    'plural' => 'Methodologies',
    'identifier_path' => 'methodology',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Methodology',
        'id' => 'id',
        'column' => 'id'
    ],
    'icon' => 'list',
    'controller' => 'MethodologyController',
    'permissions' => true,
    'exclude_routes' => [
        'create',
        'edit',
        'delete',
        'list',
        'restore',
        'datatable_all',
        'view',
    ],
];
