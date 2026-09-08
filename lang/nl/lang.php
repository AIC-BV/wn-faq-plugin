<?php

return [
    'plugin' => [
        'name' => 'FAQ',
        'description' => 'Frequently Asked Questions. Vragen en antwoorden. Geef ze een categorie, voeg uitgelichte statussen toe en beheer welke je wil weergeven op de frontend.'
    ],

    'menu' => [
        'faqs' => 'FAQs',
        'categories' => 'Categorieën'
    ],

    'controllers' => [
        'faqs' => [
            'new' => 'Nieuwe FAQ',
            'create' => 'Nieuwe FAQ',
            'update' => 'FAQ bijwerken',
            'reorder' => 'Herschik FAQs',
            'return_to_faqs' => 'Terug naar FAQs',
        ],
        'categories' => [
            'new' => 'Nieuwe categorie',
            'create' => 'Nieuwe categorie',
            'update' => 'Categorie bijwerken',
            'reorder' => 'Herschik categorieën',
            'return_to_categories' => 'Terug naar categorieën',
        ]
    ],

    'components' => [
        'categories' => [
            'title' => 'FAQ-categorieën',
            'description' => 'Lijst van FAQ-categorieën',
            'all_label' => 'Alle vragen',
            'properties' => [
                'links' => 'Links',
                'category_page' => [
                    'title' => 'Categoriepagina',
                    'description' => 'Pagina die de FAQs van één categorie toont'
                ],
                'faq_page' => [
                    'title' => 'Overzichtspagina',
                    'description' => 'Pagina die alle FAQs toont'
                ],
                'slug' => [
                    'title' => 'Categorie-slug',
                    'description' => 'Slug van de actieve categorie, gebruikt om die te markeren in de lijst'
                ],
                'sort' => [
                    'title'       => 'Sorteer',
                    'description' => 'Kies de weergave volgorde van de categorieën',
                ],
            ],
        ],
        'faqs' => [
            'title' => 'FAQs',
            'description' => 'Lijst van FAQs',
            'search_button' => 'Zoeken',
            'search_placeholder' => 'Naar wat ben je op zoek?',
            'no_results' => 'Geen FAQ gevonden.',
            'properties' => [
                'search_group' => 'Zoeken',
                'category' => [
                    'title' => 'Categorie',
                    'description' => 'Kies de categorie om weer te geven',
                    'all' => 'Alle categorieën',
                    'no_category_label' => 'Andere',
                ],
                'featured' => [
                    'title' => 'FAQs',
                    'description' => 'Kies de FAQs om weer te geven',
                    'options' => [
                        0 => 'Alleen niet-uitgelichte',
                        1 => 'Alleen uitgelichte',
                        '' => 'Alle FAQs'
                    ],
                ],
                'minSearchResults' => [
                    'title' => 'Minimum zoek resultaten',
                    'description' => 'De minimum hoeveelheid zoek resultaten om het zoek veld weer te geven. Moet een nummer zijn.',
                    'validationMessage' => 'Moet een nummer zijn',
                ],
                'no_faqs' => [
                    'title' => 'Geen FAQ\'s gevonden',
                    'description' => 'Bericht dat wordt weergegeven als er geen FAQ\'s zijn gevonden',
                ],
                'search' => [
                    'title' => 'Zoeken inschakelen',
                    'description' => 'Scakel de zoek functionaliteit in',
                ],
                'sort' => [
                    'title'       => 'Sorteer',
                    'description' => 'Kies de weergave volgorde van de FAQs',
                ],
                'translated' => [
                    'title' => 'Enkel vertaalde FAQs',
                    'description' => 'Toon enkel de FAQs vertaald in de huidige taal',
                ],
            ],
        ],
        'faqs_by_slug' => [
            'title' => 'FAQs per categorie-slug',
            'description' => 'Lijst van FAQs gefilterd op categorie-slug',
            'properties' => [
                'category_filter' => [
                    'title' => 'Categorie-slug',
                    'description' => 'Categorie-slug of routeparameter (bijvoorbeeld {{ :slug }}) om FAQs te filteren',
                ],
            ],
        ],
    ],

    'models' => [
        'general' => [
            'id' => 'ID',
            'name' => 'Naam',
            'none_options' => '-- Geen --',
            'published_status' => 'Gepubliceerd',
            'slug' => 'Slug',
            'total' => 'Totaal',
            'created_at' => 'Aangemaakt op',
            'updated_at' => 'Aangepast op',
            'featured_status' => 'Uitgelicht',
        ],
        'category' => [
            'label' => 'Categorie',
            'label_plural' => 'Categorieën',
        ],
        'faq' => [
            'label' => 'FAQ',
            'label_plural' => 'FAQs',
            'question' => 'Vraag',
            'answer' => 'Antwoord',
        ]
    ],

    'permissions' => [
        'manage_categories' => 'Beheer FAQ-categorieën',
        'manage_faqs' => 'Beheer FAQs',
    ],

    'enums' => [
        'featured_status' => [
            '0' => 'Niet uitgelicht',
            '1' => 'Uitgelicht',
        ],
        'publish_status' => [
            0 => 'Niet gepubliceerd',
            1 => 'Gepubliceerd',
            2 => 'In bewerking'
        ]
    ],

    'sorting' => [
        'sort_order_asc'  => 'Sorteervolgorde ↑ (opl)',
        'sort_order_desc' => 'Sorteervolgorde ↓ (afl)',
        'question_asc'  => 'Vraag A→Z',
        'question_desc' => 'Vraag Z→A',
        'name_asc'  => 'Naam A→Z',
        'name_desc' => 'Naam Z→A',
        'category_id_asc'  => 'Categorie (id) ↑ (opl)',
        'category_id_desc' => 'Categorie (id) ↓ (afl)',
        'created_at_asc'   => 'Aangemaakt op ↑ (opl)',
        'created_at_desc'  => 'Aangemaakt op ↓ (afl)',
        'updated_at_asc'   => 'Aangepast op ↑ (opl)',
        'updated_at_desc'  => 'Aangepast op ↓ (afl)',
        'random'           => 'Willekeurig',
    ],
];
