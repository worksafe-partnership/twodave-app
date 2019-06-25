<?php

return [
    'singular' => 'Project',
    'plural' => 'Projects',
    'identifier_path' => 'company.project',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Project',
        'id' => 'id',
        'column' => 'id'
    ],
    'icon' => 'account_balance_wallet',
    'controller' => 'ProjectController',
    'datatable' => [
        "columns" => [
        'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
        'name' => ['label' => 'Name'],
        'ref' => ['label' => 'Reference'],
        'company_id' => ['label' => 'Company'],
        'project_admin' => ['label' => 'Project Admin'],
        'principle_contractor' => ['label' => 'Principle Contractor'],
        'principle_contractor_name' => ['label' => 'Principle Contractor Name'],
        'principle_contractor_email' => ['label' => 'Principle Contractor Email'],
        'client_name' => ['label' => 'Client Name'],
        'review_timescale' => ['label' => 'Review Timescale (Overrides Company)'],
        'show_contact' => ['label' => 'Show Contact Information on VTRAMs']
    ]
    ],
    'permissions' => true
];
