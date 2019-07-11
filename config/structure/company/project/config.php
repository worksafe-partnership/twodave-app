<?php

return [
    'singular' => 'Company Project',
    'plural' => 'Company Projects',
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
                'label' => 'Principal Contractor',
                'col_type' => 'checkbox'
            ],
            'principle_contractor_name' => ['label' => 'Principal Contractor Name'],
            'review_timescale' => ['label' => 'Review Timescale'],
        ]
    ],
    'permissions' => true
];
