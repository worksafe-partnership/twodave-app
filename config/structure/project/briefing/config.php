<?php

return [
    'singular' => 'Briefing',
    'plural' => 'Briefings',
    'identifier_path' => 'project.briefing',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Briefing',
        'id' => 'id',
        'column' => 'name'
    ],
    'icon' => 'briefcase',
    'controller' => 'BriefingController',
    'datatable' => [
        "columns" => [
            'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
            'project_id' => ['label' => 'Project'],
            'vtram_id' => ['label' => 'VTRAM'],
            'name' => ['label' => 'Briefing Name'],
            'briefed_by' => ['label' => 'Briefed By'],
            'created_at' => [
                'label' => 'Created Date',
                'col_type' => 'date',
            ],
        ]
    ],
    'permissions' => true
];
