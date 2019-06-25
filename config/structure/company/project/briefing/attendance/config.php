<?php

return [
    'singular' => 'Attendance',
    'plural' => 'Attendance',
    'identifier_path' => 'company.project.briefing.attendance',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Attendance',
        'id' => 'id',
        'column' => 'id'
    ],
    'icon' => 'playlist_add_check',
    'controller' => 'AttendanceController',
    'datatable' => [
        "columns" => [
        'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
        'briefing_id' => ['label' => 'Briefing'],
        'file_id' => ['label' => 'Attendance Document']
    ]
    ],
    'sidebar' => [],
    'permissions' => true
];
