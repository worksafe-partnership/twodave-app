<?php

return [
    'singular' => 'Methodology',
    'plural' => 'Methodologies',
    'identifier_path' => 'company.project.vtram.methodology',
    'route_type' => 'index',
    'db' => [
        'model' => 'Methodology',
        'id' => 'id',
        'column' => 'id'
    ],
    'icon' => 'list',
    'controller' => 'MethodologyController',
    'datatable' => [
        "columns" => [
        'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
        'category' => ['label' => 'Category'],
        'entity' => ['label' => 'entity'],
        'entity_id' => ['label' => 'entity_id'],
        'text_before' => ['label' => 'Text Before'],
        'text_after' => ['label' => 'Text After'],
        'image' => ['label' => 'Image'],
        'image_on' => ['label' => 'Image On'],
        'list_order' => ['label' => 'list_order']
    ]
    ],
    'sidebar' => [],
    'permissions' => false
];
