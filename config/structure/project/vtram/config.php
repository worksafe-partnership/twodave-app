<?php

return [
    'singular' => 'VTRAMS',
    'plural' => 'VTRAMS',
    'identifier_path' => 'project.vtram',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Vtram',
        'id' => 'id',
        'column' => 'name'
    ],
    'icon' => 'document-add',
    'controller' => 'VtramController',
    'datatable' => [
        "columns" => [
            'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
            'number' => ['label' => 'VTRAMS Number'],
            'project_id' => ['label' => 'Project'],
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
            'resubmit_by' => [
                'label' => 'Resubmit By',
                'col_type' => 'date',
            ],
        ]
    ],
    'permissions' => true,
    'override_route_actions' => [
        '_edit' => 'edit',
        '_view' => 'view'
    ]
];
