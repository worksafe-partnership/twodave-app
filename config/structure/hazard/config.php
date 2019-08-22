<?php

return [
    'singular' => 'Hazard',
    'plural' => 'Hazards',
    'identifier_path' => 'hazard',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Hazard',
        'id' => 'id',
        'column' => 'id'
    ],
    'icon' => 'warning',
    'controller' => 'HazardController',
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
