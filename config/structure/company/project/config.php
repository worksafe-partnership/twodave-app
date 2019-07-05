<?php

return [
    'singular' => 'Project',
    'plural' => 'Projects',
    'identifier_path' => 'company.project',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Project',
        'id' => 'id',
        'column' => 'name'
    ],
    'icon' => 'account_balance_wallet',
    'controller' => 'CompanyProjectController',
    'datatable' => [
        "columns" => [
            'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
            'client_name' => ['label' => 'Client'],
            'name' => ['label' => 'Name'],
            'ref' => ['label' => 'Reference'],
            'project_admin' => ['label' => 'Project Admin'],
            'principle_contractor' => [
                'label' => 'Principle Contractor',
                'col_type' => 'checkbox'
            ],
            'principle_contractor_name' => ['label' => 'Principle Contractor Name'],
            'review_timescale' => ['label' => 'Review Timescale'],
        ]
    ],
    'permissions' => true
];
