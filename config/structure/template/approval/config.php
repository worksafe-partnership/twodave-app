<?php

return [
    'singular' => 'Template Approval',
    'plural' => 'Template Approvals',
    'identifier_path' => 'template.approval',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Approval',
        'id' => 'id',
        'column' => 'id'
    ],
    'icon' => 'tick',
    'controller' => 'TemplateApprovalController',
    'datatable' => [
        "columns" => [
            'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
            'type' => ['label' => 'Type'],
            'completed_by' => ['label' => 'Approved By'],
            'approved_date' => [ 'label' => 'Approval Date'],
            'resubmit_date' => [ 'label' => 'Resubmit Date'],
            'status_at_time' => ['label' => 'Status At Time'],
        ]
    ],
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
