<?php

return [
    'singular' => 'VTRAM',
    'plural' => 'VTRAMS',
    'identifier_path' => 'company.project.vtram',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Vtram',
        'id' => 'id',
        'column' => 'id'
    ],
    'icon' => 'document-add',
    'controller' => 'VtramController',
    'datatable' => [
        "columns" => [
        'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
        'project_id' => ['label' => 'Project'],
        'name' => ['label' => 'Name'],
        'description' => ['label' => 'Description'],
        'logo' => ['label' => 'Logo (Overrides Company Logo on VTRAMs)'],
        'reference' => ['label' => 'Reference'],
        'key_points' => ['label' => 'Key Points'],
        'havs_noise_assessment' => ['label' => 'HAVs/Noise Assessment Document'],
        'coshh_assessment' => ['label' => 'COSHH Assessment Document'],
        'review_due' => ['label' => 'Review Due'],
        'approved_date' => ['label' => 'Approved Date'],
        'original_id' => ['label' => 'original_id'],
        'revision_number' => ['label' => 'Revision Number'],
        'status' => ['label' => 'Status'],
        'created_by' => ['label' => 'Created By'],
        'updated_by' => ['label' => 'Updated By'],
        'submitted_by' => ['label' => 'Submitted By'],
        'approved_by' => ['label' => 'Approved By'],
        'date_replaced' => ['label' => 'Date Replaced'],
        'resubmit_by' => ['label' => 'Resubmit By'],
        'pre_risk_assessment_text' => ['label' => 'Pre Risk Assessment Text'],
        'post_risk_assessment_text' => ['label' => 'Post Risk Assessment Text'],
        'dynamic_risk' => ['label' => 'Dynamic Risk (Adds Dynamic Risk boxes to the VTRAM)'],
        'pdf' => ['label' => 'pdf'],
        'pages_in_pdf' => ['label' => 'pages_in_pdf'],
        'created_from' => ['label' => 'Created From'],
        'show_responsible_person' => ['label' => 'Show Responsible Person'],
        'responsible_person' => ['label' => 'Responsible Person']
    ]
    ],
    'sidebar' => [],
    'permissions' => true
];
