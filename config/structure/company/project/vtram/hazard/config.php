<?php

return [
    'singular' => 'Hazard',
    'plural' => 'Hazards',
    'identifier_path' => 'company.project.vtram.hazard',
    'route_type' => 'index',
    'db' => [
        'model' => 'Hazard',
        'id' => 'id',
        'column' => 'id'
    ],
    'icon' => 'warning',
    'controller' => 'HazardController',
    'datatable' => [
        "columns" => [
        'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
        'description' => ['label' => 'Description'],
        'entity' => ['label' => 'entity'],
        'entity_id' => ['label' => 'entity_id'],
        'control' => ['label' => 'control'],
        'risk' => ['label' => 'Risk'],
        'r_risk' => ['label' => 'Reduced Risk'],
        'list_order' => ['label' => 'list_order'],
        'at_risk' => ['label' => 'Who is at Risk'],
        'other_at_risk' => ['label' => 'Please Specify']
    ]
    ],
    'sidebar' => [],
    'permissions' => false
];
