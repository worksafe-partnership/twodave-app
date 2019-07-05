<?php

return [
    'singular' => 'VTRAM',
    'plural' => 'VTRAMS',
    'identifier_path' => 'project.tracker',
    'route_type' => 'index',
    'icon' => 'document-add',
    'controller' => 'ProjectTrackerController',
    'datatable' => [
        "columns" => [
            'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
            'name' => ['label' => 'VTRAM Name'],
            'status' => ['label' => 'Status'],
            'created_by' => ['label' => 'Created By'],
            'submitted_by' => ['label' => 'Submitted By'],
            'submitted_date' => ['label' => 'Submitted On', 'col_type' => 'date'],
            'approved_date' => ['label' => 'Approval Date', 'col_type' => 'date'],
            'approved_by' => ['label' => 'Approved By'],
            'review_due' => ['label' => 'Next Review Date', 'col_type' => 'coloured_date']
        ],
        "href" => "vtram/%ID%"
    ],
    'override_route_actions' => [
        '_datatableAll' => 'datatableAll',
    ],
    'permissions' => true,
];
