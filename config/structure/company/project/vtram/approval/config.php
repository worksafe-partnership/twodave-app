<?php

return [
    'singular' => 'Approval',
    'plural' => 'Approvals',
    'identifier_path' => 'company.project.vtram.approval',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Approval',
        'id' => 'id',
        'column' => 'id'
    ],
    'icon' => 'tick',
    'controller' => 'ApprovalController',
    'datatable' => [
        "columns" => [
        'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
        'entity' => ['label' => 'entity'],
        'entity_id' => ['label' => 'entity_id'],
        'comment' => ['label' => 'Comment'],
        'type' => ['label' => 'type'],
        'completed_by' => ['label' => 'completed_by'],
        'completed_by_id' => ['label' => 'completed_by_id'],
        'approved_date' => ['label' => 'approved_date'],
        'resubmit_date' => ['label' => 'Resubmit Date'],
        'status_at_time' => ['label' => 'status_at_time'],
        'review_document' => ['label' => 'Review Document']
    ]
    ],
    'sidebar' => [],
    'permissions' => true
];
