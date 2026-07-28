<?php

return [
    'plugin' => [
        'name' => 'FAQ',
        'description' => 'Frequently Asked Questions. Vragen en antwoorden. Geef ze een categorie, voeg uitgelichte statussen toe en beheer welke je wil weergeven op de frontend.'
    ],
    'button' => [
        'return' => 'Vorige',
        'reorder' => 'Herschikken',
        'reset_default' => 'Standaardwaarden herstellen'
    ],
    'menu' => [
        'faqs' => 'FAQs',
        'categories' => 'Categorieën',
        'settings' => 'Instellingen'
    ],
    'title' => [
        'faqs' => 'FAQ',
        'categories' => 'Categorie',
        'settings' => 'Instellingen'
    ],
    'new' => [
        'faqs' => 'Nieuwe FAQ',
        'categories' => 'Nieuwe categorie'
    ],
    'form' => [
        'total' => 'TOTAAL',
        'id' => 'ID',
        'name' => 'Naam',
        'slug' => 'Slug',
        'intro' => 'Introductie',
        'blocks' => 'Blokken',
        'blocks_add' => 'Voeg een blok toe',
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
            ],
            'category_slug' => [
                'title' => 'Categorie-slug',
                'description' => 'Zoek de FAQ-categorie op via de opgegeven slug en toon enkel de FAQs van die categorie'
            ]
        ],
        'categories' => [
            'title' => 'FAQ-categorieën',
            'description' => 'Lijst van FAQ-categorieën',
            'all_label' => 'Alle vragen',
            'settings' => [
                'links' => 'Links',
                'slug' => [
                    'title' => 'Categorie-slug',
                    'description' => 'Slug van de actieve categorie, gebruikt om die te markeren in de lijst'
                ],
                'overview_page' => [
                    'title' => 'Overzichtspagina',
                    'description' => 'Pagina die alle FAQs toont'
                ],
                'category_page' => [
                    'title' => 'Categoriepagina',
                    'description' => 'Pagina die de FAQs van één categorie toont'
                ]
            ]
        ]
    ],
    'settings' => [
        'title' => 'Titel',
        'intro' => 'Introductie',
        'no_posts_title' => 'Geen artikels bericht',
        'no_posts_description' => 'Bericht om weer te geven wanneer er geen artikels terug zijn gevonden.',
        'no_posts_found' => 'Geen artikels gevonden',
        'filter_title' => 'Filters',
        'filter_description' => 'Schakel de categoriefilter in'
    ],
    'fields' => [
        'tab_meta' => 'Meta',
        'meta_title' => 'Meta Title',
        'meta_description' => 'Meta Description',
        'og' => 'OpenGraph',
        'og_title' => 'OG Title',
        'og_description' => 'OG Description',
        'og_image' => 'OG Afbeelding'
    ],
    'blocks' => [
        'text' => 'Tekst',
        'title' => 'Titel',
        'subtitle' => 'Subtitel',
        'type' => 'Type',
        'type_default' => 'Standaard',
        'type_intro' => 'Inleiding',
        'color' => 'Achtergrondkleur',
        'margin' => 'Marge bovenaan',
        'padding' => 'Kleinere afstand bovenaan',
        'center' => 'Centreer tekst',
        'on' => 'Aan',
        'off' => 'Uit'
    ],
    'permission' => [
        'faq' => 'Beheer FAQ'
    ]
];
