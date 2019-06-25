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
];
