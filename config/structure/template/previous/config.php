<?php

return [
    'singular' => 'Template',
    'plural' => 'Templates',
    'identifier_path' => 'template.previous',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Template',
        'id' => 'id',
        'column' => 'name'
    ],
    'icon' => 'insert-template',
    'controller' => 'PreviousTemplateController',
    'datatable' => [
        "columns" => [
            'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
            'company_id' => ['label' => 'Company'],
            'name' => ['label' => 'Name'],
            'logo' => [
                'label' => 'Logo',
                'raw' => true
            ],
            'reference' => ['label' => 'Reference'],
            'approved_date' => ['label' => 'Approved Date'],
            'review_due' => ['label' => 'Review Due'],
            'revision_number' => ['label' => 'Revision Number'],
            'status' => ['label' => 'Status'],
            'submitted_by' => ['label' => 'Submitted By'],
            'approved_by' => ['label' => 'Approved By'],
            'resubmit_by' => ['label' => 'Resubmit By']
        ]
    ],
    'sidebar' => [],
    'permissions' => true,
    'exclude_route' => [
        'create',
        'edit',
        'delete',
        'permanentlyDelete',
        'restore',
        'datatable_all',
    ],
];
