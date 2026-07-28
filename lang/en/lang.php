<?php

return [
    'plugin' => [
        'name' => 'FAQ',
        'description' => 'Frequently Asked Questions. Questions and answers. Assign them to a category, add featured statusses and manage which ones are displayed on the frontend.'
    ],
    'button' => [
        'return' => 'Return',
        'reorder' => 'Reorder',
        'reset_default' => 'Reset to default'
    ],
    'menu' => [
        'faqs' => 'FAQs',
        'categories' => 'Categories',
        'settings' => 'Settings'
    ],
    'title' => [
        'faqs' => 'FAQ',
        'categories' => 'Category',
        'settings' => 'Settings'
    ],
    'new' => [
        'faqs' => 'New FAQ',
        'categories' => 'New category'
    ],
    'form' => [
        'total' => 'TOTAL',
        'id' => 'ID',
        'name' => 'Name',
        'slug' => 'Slug',
        'intro' => 'Introduction',
        'blocks' => 'Blocks',
        'blocks_add' => 'Add a block',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'question' => 'Question',
        'answer' => 'Answer',
        'category' => 'Category',
        'no_category' => '-',
        'featured_status' => [
            'title' => 'Featured',
            'featured' => 'Featured',
            'not_featured' => 'Not featured'
        ],
        'published_status' => [
            'title' => 'Published',
            'published' => 'Published',
            'not_published' => 'Hidden',
            'in_draft' => 'In progress'
        ]
    ],
    'component' => [
        'title' => 'FAQs',
        'description' => 'List of FAQs',
        'settings' => [
            'sort' => [
                'title'       => 'Sort',
                'description' => 'Choose the display order of the FAQs',
                'options'     => [
                    'category_id_asc'  => 'Category ascending',
                    'category_id_desc' => 'Category descending',
                    'created_at_asc'   => 'Created at ascending',
                    'created_at_desc'  => 'Created at descending'
                ]
            ],
            'category' => [
                'title' => 'Category',
                'description' => 'Choose which category to display',
                'all' => 'All categories',
                'no_category_label' => 'Other'
            ],
            'featured' => [
                'title' => 'FAQs',
                'description' => 'Choose which FAQs to display',
                'all' => 'All FAQs',
                'featured' => 'Featured FAQs',
                'not_featured' => 'All except featured FAQs'
            ],
            'translated' => [
                'title' => 'Translated FAQs only',
                'description' => 'Show only the translated FAQs in the current language'
            ],
            'search' => [
                'title' => 'Search enabled',
                'description' => 'Enable the search functionality',
                'button_label' => 'Search',
                'input_placeholder' => 'What are you looking for?'
            ],
            'minSearchResults' => [
                'title' => 'Search minimum results',
                'description' => 'Minimum amount of results for the search field to show. Must be a number',
                'validationMessage' => 'Must be a number'
            ],
            'category_slug' => [
                'title' => 'Category slug',
                'description' => 'Look up the FAQ category using the supplied slug value and only show FAQs from that category'
            ]
        ],
        'categories' => [
            'title' => 'FAQ Categories',
            'description' => 'List of FAQ categories',
            'all_label' => 'All questions',
            'settings' => [
                'links' => 'Links',
                'slug' => [
                    'title' => 'Category slug',
                    'description' => 'Slug of the active category, used to highlight it in the list'
                ],
                'overview_page' => [
                    'title' => 'Overview page',
                    'description' => 'Page that shows all FAQs'
                ],
                'category_page' => [
                    'title' => 'Category page',
                    'description' => 'Page that shows the FAQs of one category'
                ]
            ]
        ]
    ],
    'settings' => [
        'title' => 'Title',
        'intro' => 'Introduction',
        'no_posts_title' => 'No articles message',
        'no_posts_description' => 'Message to display when no articles are found.',
        'no_posts_found' => 'No articles found',
        'filter_title' => 'Filters',
        'filter_description' => 'Enable the category filter'
    ],
    'fields' => [
        'tab_meta' => 'Meta',
        'meta_title' => 'Meta Title',
        'meta_description' => 'Meta Description',
        'og' => 'OpenGraph',
        'og_title' => 'OG Title',
        'og_description' => 'OG Description',
        'og_image' => 'OG Image'
    ],
    'blocks' => [
        'text' => 'Text',
        'title' => 'Title',
        'subtitle' => 'Subtitle',
        'type' => 'Type',
        'type_default' => 'Default',
        'type_intro' => 'Intro',
        'color' => 'Background color',
        'margin' => 'Top margin',
        'padding' => 'Smaller top spacing',
        'center' => 'Center text',
        'on' => 'On',
        'off' => 'Off'
    ],
    'permission' => [
        'faq' => 'Manage FAQ'
    ]
];
