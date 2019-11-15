<?php

return [
    'singular' => 'Previous VTRAMS',
    'plural' => 'Previous VTRAMS',
    'identifier_path' => 'project.vtram.previous',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Vtram',
        'id' => 'id',
        'column' => 'name'
    ],
    'icon' => 'document-add',
    'controller' => 'PreviousVtramController',
    'datatable' => [
        "columns" => [
            'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
            'company_name' => ['label' => 'Company'],
            'project_id' => ['label' => 'Project'],
            'number' => ['label' => 'VTRAMS Number'],
            'name' => ['label' => 'Name'],
            'logo' => [
                'label' => 'Logo',
                'raw' => true
            ],
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
            'resubmit_by' => [
                'label' => 'Resubmit By',
                'col_type' => 'date',
            ],
        ]
    ],
    'sidebar' => [],
    'permissions' => true,
    'exclude_routes' => [
        'create',
        'edit',
        'delete',
        'permanentlyDelete',
        'restore',
    ],
    'override_route_actions' => [
        '_view' => 'view'
    ]
];
