<?php

return [
    'singular' => 'Briefing',
    'plural' => 'Briefings',
    'identifier_path' => 'company.project.briefing',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Briefing',
        'id' => 'id',
        'column' => 'id'
    ],
    'icon' => 'briefcase',
    'controller' => 'BriefingController',
    'datatable' => [
        "columns" => [
        'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
        'project_id' => ['label' => 'project_id'],
        'vtram_id' => ['label' => 'vtram_id'],
        'briefed_by' => ['label' => 'Briefed By'],
        'name' => ['label' => 'Briefing Name'],
        'notes' => ['label' => 'Notes']
    ]
    ],
    'sidebar' => [],
    'permissions' => true
];
