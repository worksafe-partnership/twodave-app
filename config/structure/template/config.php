<?php

return [
    'singular' => 'Template',
    'plural' => 'Templates',
    'identifier_path' => 'template',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Template',
        'id' => 'id',
        'column' => 'id'
    ],
    'icon' => 'insert-template',
    'controller' => 'TemplateController',
    'datatable' => [
        "columns" => [
        'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
        'company_id' => ['label' => 'Company (Leave blank to make available for all Companies)'],
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
        'resubmit_by' => ['label' => 'Resubmit By']
    ]
    ],
    'sidebar' => [],
    'permissions' => true
];
