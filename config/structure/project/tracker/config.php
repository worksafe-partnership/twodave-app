<?php

return [
    'singular' => 'VTRAMS',
    'plural' => 'VTRAMS Tracker',
    'identifier_path' => 'project.tracker',
    'route_type' => 'index',
    'icon' => 'document-add',
    'controller' => 'ProjectTrackerController',
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
        ],
        "href" => "vtram/%ID%"
    ],
    'override_route_actions' => [
        '_datatableAll' => 'datatableAll',
    ],
    'permissions' => true,
];
