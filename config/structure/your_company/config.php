<?php

return [
    'singular' => 'Your Company',
    'plural' => 'Your Company',
    'identifier_path' => 'your_company',
    'db' => [
        'model' => 'Company',
        'id' => 'id',
        'column' => 'name'
    ],
    'icon' => 'people',
    'controller' => '\App\Http\Controllers\YourCompanyController',
    'route_type' => 'resource-page',
    'sidebar' => [],
    'permissions' => true,
];
