<?php

return [
    'singular' => 'Template',
    'plural' => 'Templates',
    'identifier_path' => 'company.template',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Template',
        'id' => 'id',
        'column' => 'name'
    ],
    'icon' => 'insert-template',
    'controller' => 'CompanyTemplateController',
    'datatable' => [
        "columns" => [
            'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
            'company_id' => ['label' => 'Company'],
            'name' => ['label' => 'Name'],
            'reference' => ['label' => 'Reference'],
            'approved_date' => [
                'label' => 'Approved Date',
                'col_type' => 'date',
            ],
            'review_due' => [
                'label' => 'Review Due',
                'col_type' => 'date',
            ],
            'revision_number' => ['label' => 'Revision Number'],
            'status' => ['label' => 'Status'],
            'submitted_by' => ['label' => 'Submitted By'],
            'approved_by' => ['label' => 'Approved By'],
            'date_replaced' => ['label' => 'Date Replaced'],
            'resubmit_by' => [
                'label' => 'Resubmit By',
                'col_type' => 'date',
            ],
        ]
    ],
    'permissions' => true,
    'override_route_actions' => [
        '_edit' => 'edit',
        '_create' => 'create',
    ]
];
