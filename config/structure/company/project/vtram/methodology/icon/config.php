<?php

return [
    'singular' => 'Icon',
    'plural' => 'Icons',
    'identifier_path' => 'company.project.vtram.methodology.icon',
    'route_type' => 'index',
    'db' => [
        'model' => 'Icon',
        'id' => 'id',
        'column' => 'id'
    ],
    'icon' => 'details',
    'controller' => 'IconController',
    'datatable' => [
        "columns" => [
        'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
        'image' => ['label' => 'Image'],
        'text' => ['label' => 'Text'],
        'list_order' => ['label' => 'list_order'],
        'methodology_id' => ['label' => 'methodology_id'],
        'type' => ['label' => 'Type']
    ]
    ],
    'sidebar' => [],
    'permissions' => false
];
