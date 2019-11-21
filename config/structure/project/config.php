<?php

return [
    'singular' => 'Project',
    'plural' => 'Projects',
    'identifier_path' => 'project',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Project',
        'id' => 'id',
        'column' => 'name'
    ],
    'icon' => 'account_balance_wallet',
    'controller' => 'ProjectController',
    'datatable' => [
        "columns" => [
            'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
            'name' => ['label' => 'Name'],
            'company_id' => ['label' => 'Company'],
            'ref' => ['label' => 'Reference'],
            'project_admin' => ['label' => 'Project Admin'],
            'principle_contractor' => [
                'label' => 'Principal Contractor',
                'raw' => true
            ],
            'principle_contractor_name' => ['label' => 'Principal Contractor Name'],
            'principle_contractor_email' => ['label' => 'Principal Contractor Email'],
            'client_name' => ['label' => 'Client Name'],
            'review_timescale' => ['label' => 'Review Timescale (Overrides Company)'],
            'show_contact' => ['label' => 'Show Contact Information on VTRAMS']
        ]
    ],
    'sidebar' => [],
    'permissions' => true,
    'override_route_actions' => [
        '_view' => 'view'
    ]
];
