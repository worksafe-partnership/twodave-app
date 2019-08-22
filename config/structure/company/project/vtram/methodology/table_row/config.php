<?php

return [
    'singular' => 'Table Row',
    'plural' => 'Table Rows',
    'identifier_path' => 'company.project.vtram.methodology.table_row',
    'route_type' => 'index',
    'db' => [
        'model' => 'TableRow',
        'id' => 'id',
        'column' => 'id'
    ],
    'icon' => 'dehaze',
    'controller' => 'TableRowController',
    'datatable' => [
        "columns" => [
        'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
        'col_1' => ['label' => 'Column 1'],
        'col_2' => ['label' => 'Column 2'],
        'col_3' => ['label' => 'Column 3'],
        'col_4' => ['label' => 'Column 4'],
        'list_order' => ['label' => 'list_order'],
        'methodology_id' => ['label' => 'methodology_id'],
        'cols_filled' => ['label' => 'cols_filled']
    ]
    ],
    'sidebar' => [],
    'permissions' => false
];
