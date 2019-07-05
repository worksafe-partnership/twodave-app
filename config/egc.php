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
    'approval_type_list' => [
        'A' => 'Accepted',
        'AC' => 'Amended',
        'R' => 'Rejected',
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
            'example' => 'gfx/methodology/text.png',
        ],
        'TEXT_IMAGE' => [
            'label' => 'Text and Image',
            'view' => 'modules.company.project.methodology.text_image',
            'pdf_view' => 'pdf.methodology.text_image',
            'example' => 'gfx/methodology/text_image.png',
        ],
        'SIMPLE_TABLE' => [
            'label' => 'Simple Table',
            'view' => 'modules.company.project.methodology.simple_table',
            'pdf_view' => 'pdf.methodology.simple_table',
            'example' => 'gfx/methodology/simple_table.png',
        ],
        'COMPLEX_TABLE' => [
            'label' => 'Complex Table',
            'view' => 'modules.company.project.methodology.complex_table',
            'pdf_view' => 'pdf.methodology.complex_table',
            'example' => 'gfx/methodology/complex_table.png',
        ],
        'PROCESS' => [
            'label' => 'Process',
            'view' => 'modules.company.project.methodology.process',
            'pdf_view' => 'pdf.methodology.process',
            'example' => 'gfx/methodology/process.png',
        ],
        'ICON' => [
            'label' => 'Icon',
            'view' => 'modules.company.project.methodology.icon',
            'pdf_view' => 'pdf.methodology.icon',
            'example' => 'gfx/methodology/icon.png',
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
        'action-sign' => 'Action Sign',
        'refer_to_manual' => 'Refer to Manual',
        'ear_protection' => 'Ear Protection',
        'eye_protection' => 'Eye Protection',
        'opaque_eye_protection' => 'Opaque Eye Protection',
        'safety_footwear' => 'Safety Footwear',
        'protective_gloves' => 'Protective Gloves',
        'protective_clothing' => 'Protective Clothing',
        'wear_your_hands' => 'Wear Your Hands',
        'use_handrail' => 'Use Handrail',
        'face_shield' => 'Face Shield',
        'head_protection' => 'Head Protection',
        'high-visibility_clothing' => 'High-visibility Clothing',
        'mask' => 'Mask',
        'respiratory_protection' => 'Respiratory Protection',
        'safety_harness' => 'Safety Harness',
        'welding_mask' => 'Welding Mask',
        'safety_belts' => 'Safety Belts',
        'disconnect' => 'Disconnect',
        'barrier_cream' => 'Barrier Cream',
        'apron' => 'Apron',
        'check_guard' => 'Check Guard',
        'saw_guard' => 'Saw Guard',
        'anti-static footwear' => 'Anti-static Footwear',
    ],
    'icon_images' => [
        'action-sign' => 'gfx/icons/action_sign.png',
        'hard-hat' => 'gfx/icons/hard-hat.png',
        'refer_to_manual' => 'gfx/icons/refer_to_manual.png',
        'ear_protection' => 'gfx/icons/ear_protection.png',
        'eye_protection' => 'gfx/icons/eye_protection.png',
        'opaque_eye_protection' => 'gfx/icons/opaque_eye_protection.png',
        'safety_footwear' => 'gfx/icons/safety_footwear.png',
        'protective_gloves' => 'gfx/icons/protective_gloves.png',
        'protective_clothing' => 'gfx/icons/protective_clothing.png',
        'wear_your_hands' => 'gfx/icons/wear_your_hands.png',
        'use_handrail' => 'gfx/icons/use_handrail.png',
        'face_shield' => 'gfx/icons/face_shield.png',
        'head_protection' => 'gfx/icons/head_protection.png',
        'high-visibility_clothing' => 'gfx/icons/high-visibility_clothing.png',
        'mask' => 'gfx/icons/mask.png',
        'respiratory_protection' => 'gfx/icons/respiratory_protection.png',
        'safety_harness' => 'gfx/icons/safety_harness.png',
        'welding_mask' => 'gfx/icons/welding_mask.png',
        'safety_belts' => 'gfx/icons/safety_belts.png',
        'disconnect' => 'gfx/icons/disconnect.png',
        'barrier_cream' => 'gfx/icons/barrier_cream.png',
        'apron' => 'gfx/icons/apron.png',
        'check_guard' => 'gfx/icons/check_guard.png',
        'saw_guard' => 'gfx/icons/saw_guard.png',
        'anti-static footwear' => 'gfx/icons/anti-static footwear.png',
    ],
    'icon_types' => [
        'MAIN' => 'Main',
        'SUB' => 'Sub',
    ],
    'review_colours' => [
        'review-okay' => '#98fb98',
        'review-two-weeks' => '#eee8aa',
        'review-overdue' => '#FF0000',
    ]
];
