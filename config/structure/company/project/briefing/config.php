<?php

return [
    'singular' => 'Company Briefing',
    'plural' => 'Company Briefings',
    'identifier_path' => 'company.project.briefing',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Briefing',
        'id' => 'id',
        'column' => 'name'
    ],
    'icon' => 'briefcase',
    'controller' => 'CompanyBriefingController',
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
    'sidebar' => [],
    'permissions' => true
];
