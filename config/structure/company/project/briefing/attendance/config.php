<?php

return [
    'singular' => 'Attendance',
    'plural' => 'Attendance',
    'identifier_path' => 'company.project.briefing.attendance',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Attendance',
        'id' => 'id',
        'column' => 'name'
    ],
    'icon' => 'playlist_add_check',
    'controller' => 'AttendanceController',
    'datatable' => [
        "columns" => [
            'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
            'file_id' => [
                'label' => 'Attendance Document',
                'raw' => true
            ],
            'created_at' => [
                'label' => 'Created',
            ],
        ]
    ],
    'sidebar' => [],
    'permissions' => true
];
