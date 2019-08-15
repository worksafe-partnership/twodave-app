<?php

return [
    'singular' => 'Previous Template Approval',
    'plural' => 'Previous Template Approvals',
    'identifier_path' => 'template.previous.approval',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Approval',
        'id' => 'id',
        'column' => 'completed_by'
    ],
    'icon' => 'tick',
    'controller' => 'PreviousTemplateApprovalController',
    'datatable' => [
        "columns" => [
            'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
            'type' => ['label' => 'Type'],
            'completed_by' => ['label' => 'Approved By'],
            'approved_date' => [
                'label' => 'Approval Date',
                'col_type' => 'date',
            ],
            'resubmit_date' => [
                'label' => 'Resubmit Date',
                'col_type' => 'date',
            ],
            'status_at_time' => ['label' => 'Status At Time'],
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
        'datatable_all',
    ],
];
