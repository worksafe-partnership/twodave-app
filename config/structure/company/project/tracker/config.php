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
            'company_id' => ['label' => 'Company'],
            'number' => ['label' => 'VTRAMS Number'],
            'name' => ['label' => 'VTRAMS Name'],
            'reference' => ['label' => 'Reference'],
            'status' => ['label' => 'Status'],
            'approved_date' => ['label' => 'Approval Date', 'col_type' => 'date'],
            'external_approval_date' => ['label' => 'External Approval Date', 'col_type' => 'date'],
            'review_due' => ['label' => 'Next Review Date', 'col_type' => 'coloured_date'],
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
