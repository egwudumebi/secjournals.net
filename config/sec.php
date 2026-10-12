<?php

return [
    'parent_org' => [
        'short_name' => 'SEC',
        'full_name' => 'Software Engineering Consortium',
        'motto' => 'Fusion of Academia and Industry to provide resources for productivity and advancement of the society.',
        'description' => 'An association of more independent entities and professionals: from the Academia, companies, organizations, or governments, pooling together their resources to achieve a common goal.',
    ],

    'site' => [
        'short_name' => 'SEC Journals',
        'full_name' => 'Software Engineering Consortium Journals',
        'email' => 'info@secjournals.net',
        'website' => 'https://secjournals.net',
    ],

    'address' => [
        'line1' => 'Room 1, PG Lab, JUPEB Building Upstairs',
        'line2' => 'Science Village, Nnamdi Azikiwe University Awka',
        'line3' => 'Anambra State, Nigeria',
        'line4' => '',
    ],

    'phones' => [
        '+234-8035410260',
        '+234-7035616299',
    ],

    'bank' => [
        'bank_name' => '',
        'account_name' => 'Software Engineering Consortium',
        'account_number' => '',
        'evidence_email' => 'info@secjournals.net',
        'payment_contacts' => [
            [
                'role' => 'Financial Secretary',
                'name' => '',
                'phone' => '+234-8035410260',
            ],
        ],
    ],

    'social' => [
        'facebook' => '',
        'linkedin' => '',
        'x' => '',
        'instagram' => '',
        'youtube' => '',
    ],

    'fees' => [
        'membership_registration' => 20000,
        'manuscript_review' => 10000,
        'peer_review_processing' => 5000,
        'turnitin_check' => 5000,
        'publication' => 30000,
        'currency' => 'NGN',
    ],

    'consortium_officers' => [
        ['role' => 'President', 'name' => 'To be announced'],
        ['role' => 'Vice President', 'name' => 'To be announced'],
        ['role' => 'Financial Secretary', 'name' => 'To be announced'],
        ['role' => 'Executive Secretary', 'name' => 'To be announced'],
        ['role' => 'Membership Chairman', 'name' => 'To be announced'],
        ['role' => 'Conference Chairman', 'name' => 'To be announced'],
    ],

    'journal_editorial_roles' => [
        'Chief Editor',
        'Managing Editor',
        'Associate Editor',
        'Section Editor',
        'Editorial Board Member',
        'Advisory Board Member',
        'Copy Editor',
        'Production Editor',
    ],

    'journals' => [
        [
            'slug' => 'software-engineering',
            'short_name' => 'SEC-JSE',
            'name' => 'SEC Journal of Software Engineering',
            'description' => 'Peer-reviewed research in software engineering methods, systems, quality, and industrial practice.',
            'sort_order' => 1,
        ],
        [
            'slug' => 'electronic-electrical-engineering',
            'short_name' => 'SEC-JEEE',
            'name' => 'SEC Journal of Electronic and Electrical Engineering',
            'description' => 'Scholarship spanning electronics, electrical systems, power, and related engineering applications.',
            'sort_order' => 2,
        ],
        [
            'slug' => 'communication-media-engineering',
            'short_name' => 'SEC-JCME',
            'name' => 'SEC Journal of Communication and Media Engineering',
            'description' => 'Research at the intersection of communication systems, media technologies, and engineering practice.',
            'sort_order' => 3,
        ],
        [
            'slug' => 'artificial-intelligence-prompt-engineering',
            'short_name' => 'SEC-JAIPE',
            'name' => 'SEC Journal of Artificial Intelligence and Prompt Engineering',
            'description' => 'Advances in artificial intelligence, machine learning, and prompt engineering for research and industry.',
            'sort_order' => 4,
        ],
        [
            'slug' => 'management-business-engineering',
            'short_name' => 'SEC-JMBE',
            'name' => 'SEC Journal of Management and Business Engineering',
            'description' => 'Engineering-informed research in management, operations, and business systems.',
            'sort_order' => 5,
        ],
    ],

    'motto' => 'Fusion of Academia and Industry for Productivity and Societal Advancement',

    'opening_statement' => 'The Software Engineering Consortium (SEC) brings academia and industry together to advance engineering scholarship, professional practice, and societal productivity.

SEC Journals provide peer-reviewed venues for rigorous research across software, electronic and electrical engineering, communication and media engineering, artificial intelligence and prompt engineering, and management and business engineering.

Each journal maintains its own editorial leadership while sharing SEC’s commitment to quality, openness, and impact.',

    'about' => 'The Software Engineering Consortium (SEC) is an association of independent entities and professionals from academia, companies, organizations, and governments, pooling resources to achieve a common goal: advancing engineering knowledge and practice for the good of society.

SEC Journals is the consortium’s publishing platform. Authors submit to a specific SEC journal; each title has its own editorial structure, including Chief Editor and Managing Editor, while remaining under the SEC umbrella.',

    'vision' => 'To be a leading consortium platform where academic and industry engineering research strengthens productivity and societal advancement.',

    'mission' => 'SEC Journals publish rigorous, relevant research across its constituent engineering journals; support authors and reviewers; and connect academia with industry practice.',

    'areas_of_interest' => [
        'Software engineering methods, tools, and quality assurance',
        'Electronic and electrical systems and applications',
        'Communication systems and media engineering',
        'Artificial intelligence, machine learning, and prompt engineering',
        'Management and business engineering',
        'Industry–academia collaboration and technology transfer',
    ],

    'what_we_publish' => [
        'Original research articles',
        'Review articles',
        'Short communications',
        'Case studies from industry and practice',
        'Technical notes',
    ],

    'why_publish' => [
        [
            'title' => 'Multi-journal platform',
            'body' => 'Publish in a dedicated SEC journal while benefiting from a shared consortium infrastructure for membership, review, and dissemination.',
        ],
        [
            'title' => 'Academia–industry focus',
            'body' => 'SEC intentionally bridges scholarly research and industrial engineering practice.',
        ],
        [
            'title' => 'Permanent article landing pages',
            'body' => 'Each published article receives a stable public landing page suitable for future DOI registration.',
        ],
    ],

    'author_guidelines' => [
        'intro' => 'Manuscripts should be original, clearly written, and prepared according to the target SEC journal’s scope. Submissions are handled through the SEC Journals member portal.',
        'sections' => [
            [
                'title' => 'Scope',
                'body' => 'Select the SEC journal that best matches your manuscript. Cross-disciplinary work may be redirected by editors when appropriate.',
            ],
            [
                'title' => 'Format',
                'body' => 'Submit manuscripts in DOCX unless a journal-specific production PDF workflow is indicated. Include title, authors, affiliations, abstract, keywords, and references.',
            ],
            [
                'title' => 'Ethics',
                'body' => 'Authors must confirm originality, disclose conflicts of interest, and obtain required permissions for third-party material.',
            ],
        ],
    ],
];
