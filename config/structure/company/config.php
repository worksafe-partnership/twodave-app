<?php

return [
    'singular' => 'Company',
    'plural' => 'Companies',
    'identifier_path' => 'company',
    'route_type' => 'resource',
    'db' => [
        'model' => 'Company',
        'id' => 'id',
        'column' => 'name'
    ],
    'icon' => 'people',
    'controller' => 'CompanyController',
    'datatable' => [
        "columns" => [
            'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
            'logo' => [
                'label' => 'Logo',
                'raw' => true,
            ],
            'name' => ['label' => 'Name'],
            'email' => ['label' => 'Contact Email'],
            'phone' => ['label' => 'Phone Number'],
            'fax' => ['label' => 'Fax Number'],
            'primary_colour' => [
                'label' => 'Primary Colour',
                'raw' => true
            ],
            'vtrams_name' => ['label' => 'VTRAMs Name'],
            'review_timescale' => ['label' => 'Review Timescale'],
        ]
    ],
    'sidebar' => [],
    'permissions' => true
];
