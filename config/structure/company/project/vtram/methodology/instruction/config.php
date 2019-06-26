<?php

return [
    'singular' => 'Instruction',
    'plural' => 'Instructions',
    'identifier_path' => 'company.project.vtram.methodology.instruction',
    'route_type' => 'index',
    'db' => [
        'model' => 'Instruction',
        'id' => 'id',
        'column' => 'id'
    ],
    'icon' => 'view_list',
    'controller' => 'InstructionController',
    'datatable' => [
        "columns" => [
        'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
        'description' => ['label' => 'Description'],
        'label' => ['label' => 'Label'],
        'heading' => ['label' => 'Heading'],
        'list_order' => ['label' => 'list_order'],
        'image' => ['label' => 'Image'],
        'methodology_id' => ['label' => 'methodology_id']
    ]
    ],
    'sidebar' => [],
    'permissions' => false
];
