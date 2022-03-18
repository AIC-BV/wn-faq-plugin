<?php

return [
    'plugin' => [
        'name' => 'FAQ',
        'description' => 'Frequently Asked Questions. Vragen en antwoorden. Geef ze een categorie, voeg uitgelichte statussen toe en beheer welke je wil weergeven op de frontend.'
    ],
    'button' => [
        'return' => 'Vorige'
    ],
    'menu' => [
        'faqs' => 'FAQs',
        'categories' => 'Categorieën'
    ],
    'title' => [
        'faqs' => 'FAQ',
        'categories' => 'Categorie'
    ],
    'new' => [
        'faqs' => 'Nieuwe FAQ',
        'categories' => 'Nieuwe categorie'
    ],
    'form' => [
        'total' => 'TOTAAL',
        'id' => 'ID',
        'name' => 'Naam',
        'created_at' => 'Aangemaakt op',
        'updated_at' => 'Aangepast op',
        'question' => 'Vraag',
        'answer' => 'Antwoord',
        'category' => 'Categorie',
        'no_category' => '-',
        'featured_status' => [
            'title' => 'Uitgelicht',
            'featured' => 'Uitgelicht',
            'not_featured' => 'Niet uitgelicht'
        ],
        'published_status' => [
            'title' => 'Gepubliceerd',
            'published' => 'Gepubliceerd',
            'not_published' => 'Verborgen',
            'in_draft' => 'Bezig'
        ]
    ],
    'component' => [
        'title' => 'FAQs',
        'description' => 'Lijst van FAQs',
        'settings' => [
            'sort' => [
                'title'       => 'Sorteer',
                'description' => 'Kies de weergave volgorde van de FAQs',
                'options'     => [
                    'category_id_asc'  => 'Categorie oplopend',
                    'category_id_desc' => 'Categorie aflopend',
                    'created_at_asc'   => 'Aangemaakt op oplopend',
                    'created_at_desc'  => 'Aangemaakt op aflopend'
                ]
            ],
            'category' => [
                'title' => 'Categorie',
                'description' => 'Kies de categorie om weer te geven',
                'all' => 'Alle categorieën',
                'no_category_label' => 'Andere'
            ],
            'featured' => [
                'title' => 'FAQs',
                'description' => 'Kies de FAQs om weer te geven',
                'all' => 'Alle FAQs',
                'featured' => 'Uitgelichte FAQs',
                'not_featured' => 'Alle behalve uitgelichte FAQs'
            ],
            'translated' => [
                'title' => 'Enkel vertaalde FAQs',
                'description' => 'Toon enkel de FAQs vertaald in de huidige taal'
            ],
            'search' => [
                'title' => 'Zoeken inschakelen',
                'description' => 'Scakel de zoek functionaliteit in',
                'button_label' => 'Zoeken',
                'input_placeholder' => 'Naar wat ben je op zoek?'
            ],
            'minSearchResults' => [
                'title' => 'Minimum zoek resultaten',
                'description' => 'De minimum hoeveelheid zoek resultaten om het zoek veld weer te geven. Moet een nummer zijn.',
                'validationMessage' => 'Moet een nummer zijn'
            ]
        ]
    ],
    'permission' => [
        'faq' => 'Beheer FAQ'
    ]
];
