<?php

return [
    'singular' => 'Company',
    'plural' => 'Companies',
    'identifier_path' => 'company',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Company',
        'id' => 'id',
        'column' => 'id'
    ],
    'icon' => 'people',
    'controller' => 'CompanyController',
    'datatable' => [
        "columns" => [
        'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
        'name' => ['label' => 'Name'],
        'review_timescale' => ['label' => 'Review Timescale'],
        'vtrams_name' => ['label' => 'VTRAMs Name'],
        'email' => ['label' => 'Contact Email'],
        'phone' => ['label' => 'Phone Number'],
        'fax' => ['label' => 'Fax Number'],
        'low_risk_character' => ['label' => 'Low Risk Label'],
        'med_risk_character' => ['label' => 'Medium Risk Label'],
        'high_risk_character' => ['label' => 'High Risk Label'],
        'no_risk_character' => ['label' => 'No Risk Label'],
        'primary_colour' => ['label' => 'Primary Colour'],
        'secondary_colour' => ['label' => 'Secondary Colour'],
        'light_text' => ['label' => 'Light Text'],
        'accept_label' => ['label' => 'Accept Label'],
        'amend_label' => ['label' => 'Amend Label'],
        'reject_label' => ['label' => 'Reject Label'],
        'logo' => ['label' => 'Logo']
    ]
    ],
    'sidebar' => [],
    'permissions' => true
];
