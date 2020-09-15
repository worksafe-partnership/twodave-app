<?php

return [
    'singular' => 'VTRAMS',
    'plural' => 'VTRAMS',
    'identifier_path' => 'company.project.vtram',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Vtram',
        'id' => 'id',
        'column' => 'name'
    ],
    'icon' => 'document-add',
    'controller' => 'CompanyVtramController',
    'datatable' => [
        "columns" => [
            'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
            'company_name' => ['label' => 'Company'],
            'number' => ['label' => 'VTRAMS Number'],
            'name' => ['label' => 'Name'],
            'reference' => ['label' => 'Reference'],
            'status' => ['label' => 'Status'],
            'approved_date' => [ 'label' => 'Approved Date', 'col_type' => 'date', ],
            'external_approval_date' => [ 'label' => 'External Approved Date', 'col_type' => 'date', ],
            'review_due' => [ 'label' => 'Review Due', 'col_type' => 'date', ],
            'revision_number' => ['label' => 'Revision Number'],
            'submitted_by' => ['label' => 'Submitted By'],
            'approved_by' => ['label' => 'Approved By'],
            'resubmit_by' => [ 'label' => 'Resubmit By', 'col_type' => 'date', ],
        ]
    ],
    'sidebar' => [],
    'permissions' => true,
    'override_route_actions' => [
        '_edit' => 'edit',
        '_create' => 'create',
    ]
];
