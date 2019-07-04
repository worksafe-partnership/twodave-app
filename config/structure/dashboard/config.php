<?php

return [
    'singular' => 'Dashboard',
    'plural' => 'Dashboard',
    'identifier_path' => 'dashboard',
    'route_type' => 'resource-page',
    'icon' => 'dashboard',
    'controller' => 'DashboardController',
    'permissions' => true,
    'sidebar' => ['order' => 1],
    'override_route_actions' => [
        '_view' => 'view',
    ],
];
