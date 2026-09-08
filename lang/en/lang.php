<?php

return [
    'plugin' => [
        'name' => 'FAQ',
        'description' => 'Frequently Asked Questions. Questions and answers. Assign them to a category, add featured statusses and manage which ones are displayed on the frontend.'
    ],

    'menu' => [
        'faqs' => 'FAQs',
        'categories' => 'Categories'
    ],

    'controllers' => [
        'faqs' => [
            'new' => 'New FAQ',
            'create' => 'Create FAQ',
            'update' => 'Update FAQ',
            'reorder' => 'Reorder FAQs',
            'return_to_faqs' => 'Return to FAQs',
        ],
        'categories' => [
            'new' => 'New category',
            'create' => 'Create category',
            'update' => 'Update category',
            'reorder' => 'Reorder categories',
            'return_to_categories' => 'Return to categories',
        ]
    ],

    'components' => [
        'categories' => [
            'title' => 'FAQ Categories',
            'description' => 'List of FAQ categories',
            'all_label' => 'All questions',
            'properties' => [
                'links' => 'Links',
                'category_page' => [
                    'title' => 'Category page',
                    'description' => 'Page that shows the FAQs of one category'
                ],
                'faq_page' => [
                    'title' => 'Overview page',
                    'description' => 'Page that shows all FAQs'
                ],
                'slug' => [
                    'title' => 'Category slug',
                    'description' => 'Slug of the active category, used to highlight it in the list'
                ],
                'sort' => [
                    'title'       => 'Sort',
                    'description' => 'Choose the display order of the categories',
                ],
            ],
        ],
        'faqs' => [
            'title' => 'FAQs',
            'description' => 'List of FAQs',
            'search_button' => 'Search',
            'search_placeholder' => 'What are you looking for?',
            'no_results' => 'No FAQ found.',
            'properties' => [
                'search_group' => 'Search',
                'category' => [
                    'title' => 'Category',
                    'description' => 'Choose which category to display',
                    'all' => 'All categories',
                    'no_category_label' => 'Other',
                ],
                'featured' => [
                    'title' => 'FAQs',
                    'description' => 'Choose which FAQs to display',
                    'options' => [
                        0 => 'All except featured',
                        1 => 'Featured only',
                        '' => 'All FAQs'
                    ],
                ],
                'minSearchResults' => [
                    'title' => 'Search minimum results',
                    'description' => 'Minimum amount of results for the search field to show. Must be a number',
                    'validationMessage' => 'Must be a number',
                ],
                'no_faqs' => [
                    'title'       => 'No FAQs message',
                    'description' => 'Message to show when no FAQs are found.',
                ],
                'search' => [
                    'title' => 'Search enabled',
                    'description' => 'Enable the search functionality',
                ],
                'sort' => [
                    'title'       => 'Sort',
                    'description' => 'Choose the display order of the FAQs',
                ],
                'translated' => [
                    'title' => 'Translated FAQs only',
                    'description' => 'Show only the translated FAQs in the current language',
                ],
            ],
        ],
        'faqs_by_slug' => [
            'title' => 'FAQs by category slug',
            'description' => 'List FAQs filtered by a category slug',
            'properties' => [
                'category_filter' => [
                    'title' => 'Category slug',
                    'description' => 'Category slug or route parameter (for example {{ :slug }}) used to filter FAQs',
                ],
            ],
        ],
    ],

    'models' => [
        'general' => [
            'id' => 'ID',
            'name' => 'Name',
            'none_options' => '-- None --',
            'published_status' => 'Published',
            'slug' => 'Slug',
            'total' => 'Total',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'featured_status' => 'Featured',
        ],
        'category' => [
            'label' => 'Category',
            'label_plural' => 'Categories',
        ],
        'faq' => [
            'label' => 'FAQ',
            'label_plural' => 'FAQs',
            'question' => 'Question',
            'answer' => 'Answer',
        ]
    ],

    'permissions' => [
        'manage_categories' => 'Manage FAQ Categories',
        'manage_faqs' => 'Manage FAQs',
    ],

    'enums' => [
        'featured_status' => [
            '0' => 'Not featured',
            '1' => 'Featured',
        ],
        'publish_status' => [
            0 => 'Not published',
            1 => 'Published',
            2 => 'In draft'
        ],
    ],

    'sorting' => [
        'sort_order_asc'  => 'Sort order ↑ (asc)',
        'sort_order_desc' => 'Sort order ↓ (desc)',
        'question_asc'  => 'Question A→Z',
        'question_desc' => 'Question Z→A',
        'name_asc'  => 'Name A→Z',
        'name_desc' => 'Name Z→A',
        'category_id_asc'  => 'Category (id) ↑ (asc)',
        'category_id_desc' => 'Category (id) ↓ (desc)',
        'created_at_asc'   => 'Created at ↑ (asc)',
        'created_at_desc'  => 'Created at ↓ (desc)',
        'updated_at_asc'   => 'Updated at ↑ (asc)',
        'updated_at_desc'  => 'Updated at ↓ (desc)',
        'random'           => 'Random',
    ],
];
