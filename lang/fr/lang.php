<?php

return [
    'plugin' => [
        'name' => 'FAQ',
        'description' => 'Foire aux questions. Questions et réponses. Classez-les par catégorie, attribuez-leur un statut « en vedette » et gérez celles qui s’affichent sur votre site..'
    ],

    'menu' => [
        'faqs' => 'FAQs',
        'categories' => 'Catégories'
    ],

    'controllers' => [
        'faqs' => [
            'new' => 'Nouvelle FAQ',
            'create' => 'Créer une FAQ',
            'update' => 'Mettre à jour la FAQ',
            'reorder' => 'Réorganiser les FAQs',
            'return_to_faqs' => 'Retour aux FAQs',
        ],
        'categories' => [
            'new' => 'Nouvelle catégorie',
            'create' => 'Créer une catégorie',
            'update' => 'Mettre à jour la catégorie',
            'reorder' => 'Réorganiser les catégories',
            'return_to_categories' => 'Retour aux catégories',
        ]
    ],

    'components' => [
        'categories' => [
            'title' => 'Catégories de FAQ',
            'description' => 'Liste des catégories de FAQ',
            'all_label' => 'Toutes les questions',
            'properties' => [
                'links' => 'Liens',
                'category_page' => [
                    'title' => 'Page de catégorie',
                    'description' => 'Page qui affiche les FAQs d’une catégorie'
                ],
                'faq_page' => [
                    'title' => 'Page d’aperçu',
                    'description' => 'Page qui affiche toutes les FAQs'
                ],
                'slug' => [
                    'title' => 'Slug de catégorie',
                    'description' => 'Slug de la catégorie active, utilisé pour la mettre en évidence dans la liste'
                ],
                'sort' => [
                    'title'       => 'Tri',
                    'description' => 'Choisissez l’ordre d’affichage des catégories',
                ],
            ],
        ],
        'faqs' => [
            'title' => 'FAQs',
            'description' => 'Liste des FAQs',
            'search_button' => 'Rechercher',
            'search_placeholder' => 'Que cherchez-vous ?',
            'no_results' => 'Aucune FAQ trouvée.',
            'properties' => [
                'search_group' => 'Recherche',
                'category' => [
                    'title' => 'Catégorie',
                    'description' => 'Choisissez la catégorie de FAQs à afficher',
                    'all' => 'Toutes les catégories',
                    'no_category_label' => 'Aucune catégorie',
                ],
                'featured' => [
                    'title' => 'FAQs',
                    'description' => 'Choisissez les FAQs à afficher',
                    'options' => [
                        0 => 'Toutes sauf à la une',
                        1 => 'À la une uniquement',
                        '' => 'Toutes les FAQs'
                    ],
                ],
                'minSearchResults' => [
                    'title' => 'Résultats minimum pour la recherche',
                    'description' => 'Nombre minimum de résultats à afficher dans le champ de recherche. Doit être un nombre.',
                    'validationMessage' => 'Doit être un nombre',
                ],
                'no_faqs' => [
                    'title'       => 'Message pour aucune FAQ',
                    'description' => 'Message à afficher lorsqu’aucune FAQ n’est trouvée.',
                ],
                'search' => [
                    'title' => 'Reccherche',
                    'description' => 'Activer le champ de recherche pour les FAQs.',
                ],
                'sort' => [
                    'title'       => 'Tri',
                    'description' => 'Choisissez l’ordre d’affichage des FAQs',
                ],
                'translated' => [
                    'title' => 'FAQs traduites uniquement',
                    'description' => 'Affichez uniquement les FAQs traduites dans la langue actuelle',
                ],
            ],
        ],
        'faqs_by_slug' => [
            'title' => 'FAQs par slug de catégorie',
            'description' => 'Liste des FAQs filtrées par slug de catégorie',
            'properties' => [
                'category_filter' => [
                    'title' => 'Slug de catégorie',
                    'description' => 'Slug de catégorie ou paramètre de route (par exemple {{ :slug }}) utilisé pour filtrer les FAQs',
                ],
            ],
        ],
    ],

    'models' => [
        'general' => [
            'id' => 'ID',
            'name' => 'Nom',
            'none_options' => '-- Aucune --',
            'published_status' => 'Publié',
            'slug' => 'Slug',
            'total' => 'Total',
            'created_at' => 'Créé le',
            'updated_at' => 'Mis à jour le',
            'featured_status' => 'À la une',
        ],
        'category' => [
            'label' => 'Catégorie',
            'label_plural' => 'Catégories',
        ],
        'faq' => [
            'label' => 'FAQ',
            'label_plural' => 'FAQs',
            'question' => 'Question',
            'answer' => 'Réponse',
        ]
    ],

    'permissions' => [
        'manage_categories' => 'Gérer les catégories de FAQ',
        'manage_faqs' => 'Gérer les FAQs'
    ],

    'enums' => [
        'featured_status' => [
            '0' => 'Non mise en avant',
            '1' => 'À la une',
        ],
        'publish_status' => [
            0 => 'Non publié',
            1 => 'Publié',
            2 => 'En cours'
        ]
    ],

    'sorting' => [
        'sort_order_asc'  => 'Ordre de tri ↑ (asc)',
        'sort_order_desc' => 'Ordre de tri ↓ (desc)',
        'question_asc'  => 'Question A→Z',
        'question_desc' => 'Question Z→A',
        'name_asc'  => 'Nom A→Z',
        'name_desc' => 'Nom Z→A',
        'category_id_asc'  => 'Catégorie (id) ↑ (asc)',
        'category_id_desc' => 'Catégorie (id) ↓ (desc)',
        'created_at_asc'   => 'Date création ↑ (asc)',
        'created_at_desc'  => 'Date création ↓ (desc)',
        'updated_at_asc'   => 'Date mise à jour ↑ (asc)',
        'updated_at_desc'  => 'Date mise à jour ↓ (desc)',
        'random'           => 'Aléatoire',
    ],
];
