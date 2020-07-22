<?php

return [
    'singular' => 'VTRAMS Tracker',
    'plural' => 'VTRAMS Tracker',
    'identifier_path' => 'company.project.tracker',
    'route_type' => 'index',
    'icon' => 'document-add',
    'controller' => 'CompanyProjectTrackerController',
    'datatable' => [
        "columns" => [
            'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
            'number' => ['label' => 'VTRAMS Number'],
            'company_id' => ['label' => 'Company'],
            'name' => ['label' => 'VTRAMS Name'],
            'status' => ['label' => 'Status'],
            'created_by' => ['label' => 'Created By'],
            'submitted_by' => ['label' => 'Submitted By'],
            'submitted_date' => ['label' => 'Submitted On', 'col_type' => 'date'],
            'approved_date' => ['label' => 'Approval Date', 'col_type' => 'date'],
            'external_approval_date' => ['label' => 'External Approval Date', 'col_type' => 'date'],
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
