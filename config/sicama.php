<?php

/**
 * Compatibility alias: historical "sicama" config keys now resolve to SEC.
 * Prefer config('sec.*') in new code.
 */
$sec = require __DIR__.'/sec.php';

return [
    'parent_org' => $sec['parent_org'],
    'journal' => [
        'short_name' => $sec['site']['short_name'],
        'full_name' => $sec['site']['full_name'],
        'description' => $sec['about'],
    ],
    'bot_resolution' => [
        'title' => 'SEC Consortium Leadership',
        'meeting_date' => null,
        'signed_date' => null,
        'signed_by' => '',
        'signed_role' => '',
        'summary' => $sec['parent_org']['description'],
        'decisions' => [],
    ],
    'interim_exco' => [
        'term_start' => null,
        'term_duration' => null,
        'note' => 'Software Engineering Consortium executive officers.',
        'members' => $sec['consortium_officers'],
    ],
];
