<?php

return [
    'singular' => 'User',
    'plural' => 'Users',
    'identifier_path' => 'company.user',
    'db' => [
        'model' => 'User',
        'id' => 'id',
        'column' => 'name'
    ],
    'icon' => 'account_circle',
    'controller' => 'CompanyWorksafeUserController',
    'route_type' => 'resource',
    'datatable' => [
        "columns" => [
            "id" => ["visible" => false, "searchable" => false],
            "company_name" => ["label" => "Company"],
            "name" => ["label" => "Name"],
            "email" => ["label" => "Email"]
        ],
    ],
    'custom_blade' => "egl::modules.user.display",
    'permissions' => true
];
