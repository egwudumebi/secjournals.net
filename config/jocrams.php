<?php

/**
 * Compatibility alias: historical "jocrams" config keys now resolve to SEC Journals.
 * Prefer config('sec.*') in new code.
 */
$sec = require __DIR__.'/sec.php';

return [
    'issn' => null,
    'motto' => $sec['motto'],
    'email' => $sec['site']['email'],
    'website' => $sec['site']['website'],
    'address' => $sec['address'],
    'phones' => $sec['phones'],
    'bank' => $sec['bank'],
    'fees' => $sec['fees'],
    'social' => $sec['social'],
    'opening_statement' => $sec['opening_statement'],
    'about' => $sec['about'],
    'vision' => $sec['vision'],
    'mission' => $sec['mission'],
    'areas_of_interest' => $sec['areas_of_interest'],
    'what_we_publish' => $sec['what_we_publish'],
    'why_publish' => $sec['why_publish'],
    'author_guidelines' => $sec['author_guidelines'],
    'editorial_leadership' => [],
    'editor_in_chief_message' => [
        'title' => 'Welcome to SEC Journals',
        'body' => "The Software Engineering Consortium provides a multi-journal platform for rigorous engineering scholarship.\n\nEach SEC journal maintains its own editorial leadership while sharing consortium standards for peer review and publication.",
        'signatory_name' => 'Editorial Office',
        'signatory_title' => 'Software Engineering Consortium',
    ],
];
