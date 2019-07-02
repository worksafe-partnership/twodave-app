<?php

return [
    'singular' => 'VTRAM',
    'plural' => 'VTRAMS',
    'identifier_path' => 'company.project.vtram',
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
            'project_id' => ['label' => 'Project'],
            'name' => ['label' => 'Name'],
            'reference' => ['label' => 'Reference'],
            'approved_date' => ['label' => 'Approved Date'],
            'review_due' => ['label' => 'Review Due'],
            'revision_number' => ['label' => 'Revision Number'],
            'status' => ['label' => 'Status'],
            'submitted_by' => ['label' => 'Submitted By'],
            'approved_by' => ['label' => 'Approved By'],
            'resubmit_by' => ['label' => 'Resubmit By'],
        ]
    ],
    'sidebar' => [],
    'permissions' => true
];
