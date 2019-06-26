<?php

return [
    'dates' => [
        'date' => 'd/m/Y',
        'datetime' => "d/m/Y H:i",
        'time' => 'H:i'
    ],
    'list' => [
        'honorifics' => [
            'MR' => 'Mr',
            'MRS' => 'Mrs',
            'MISS' => 'Miss',
            'MS' => 'Ms',
            'DR' => 'Dr',
            'PROFESSOR' => 'Prof',
            'NONE' => 'None'
        ],
    ],
    'search' => [
        'on' => true,
        'url' => '/egsearch'
    ],
    'sidebar_logo_href' => [
        'on' => true,
        'url' => '/'
    ],
    'review_timescales' => [
        0 => 'No Review Schedule',
        1 => '1 Month',
        2 => '2 Months',
        3 => '3 Months',
        4 => '4 Months',
        5 => '5 Months',
        6 => '6 Months',
        7 => '7 Months',
        8 => '8 Months',
        9 => '9 Months',
        10 => '10 Months',
        11 => '11 Months',
        12 => '1 Year',
    ],
    'vtram_status' => [
        'NEW' => 'New',
        'PENDING' => 'Pending',
        'AWAITING_EXTERNAL' => 'Awaiting External Approval',
        'EXTERNAL_REJECT' => 'Rejected Externally',
        'REJECTED' => 'Rejected',
        'CURRENT' => 'Current',
        'PREVIOUS' => 'Previous',
    ],
    'approval_type' => [
        'A' => 'Accepted',
        'AC' => 'Amended',
        'R' => 'Rejected',
    ],
    'pc_approval_type' => [
        'PC_A' => 'Principle Contractor Accepted',
        'PC_AC' => 'Principle Contractor Amended',
        'PC_R' => 'Principle Contractor Rejected',
    ],
    'hazard_who_risk' => [
        'C' => 'Client',
        'M' => 'Management',
        'S' => 'Supervisor',
        'E' => 'Employees',
        'S' => 'Sub-contractor',
        'T' => 'Third Party',
        'P' => 'Public',
        'R' => 'Residents',
        'O' => 'Other - please specify'
    ],
    'methodology_categories' => [
        'TEXT' => [
            'label' => 'Text',
            'view' => 'modules.company.project.methodology.text',
            'pdf_view' => 'pdf.methodology.text',
            'gfx/methodology/text.png',
        ],
        'TEXT_IMAGE' => [
            'label' => 'Text and Image',
            'view' => 'modules.company.project.methodology.text_image',
            'pdf_view' => 'pdf.methodology.text_image',
            'gfx/methodology/text_image.png',
        ],
        'SIMPLE_TABLE' => [
            'label' => 'Simple Table',
            'view' => 'modules.company.project.methodology.simple_table',
            'pdf_view' => 'pdf.methodology.simple_table',
            'gfx/methodology/simple_table.png',
        ],
        'COMPLEX_TABLE' => [
            'label' => 'Complex Table',
            'view' => 'modules.company.project.methodology.complex_table',
            'pdf_view' => 'pdf.methodology.complex_table',
            'gfx/methodology/complex_table.png',
        ],
        'PROCESS' => [
            'label' => 'Process',
            'view' => 'modules.company.project.methodology.process',
            'pdf_view' => 'pdf.methodology.process',
            'gfx/methodology/process.png',
        ],
        'ICON' => [
            'label' => 'Icon',
            'view' => 'modules.company.project.methodology.icon',
            'pdf_view' => 'pdf.methodology.icon',
            'gfx/methodology/icon.png',
        ],
    ],
    'methodology_list' => [
        'TEXT' => 'Text',
        'TEXT_IMAGE' => 'Text and Image',
        'SIMPLE_TABLE' => 'Simple Table',
        'COMPLEX_TABLE' => 'Complex Table',
        'PROCESS' => 'Process',
        'ICON' => 'Icon',
    ],
    'first_last' => [
        'FIRST' => 'First',
        'LAST' => 'Last',
    ],
    'icons' => [
        'hard-hat' => 'Hard Hat',
        // ETC
    ],
    'icon_images' => [
        'hard-hat' => 'gfx/icons/hard-hat.png',
        // ETC
    ],
    'icon_types' => [
        'MAIN' => 'Main',
        'SUB' => 'Sub',
    ],
];
