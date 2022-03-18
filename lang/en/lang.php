<?php

return [
    'plugin' => [
        'name' => 'FAQ',
        'description' => 'Frequently Asked Questions. Questions and answers. Assign them to a category, add featured statusses and manage which ones are displayed on the frontend.'
    ],
    'button' => [
        'return' => 'Return'
    ],
    'menu' => [
        'faqs' => 'FAQs',
        'categories' => 'Categories'
    ],
    'title' => [
        'faqs' => 'FAQ',
        'categories' => 'Category'
    ],
    'new' => [
        'faqs' => 'New FAQ',
        'categories' => 'New category'
    ],
    'form' => [
        'total' => 'TOTAL',
        'id' => 'ID',
        'name' => 'Name',
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
            ]
        ]
    ],
    'permission' => [
        'faq' => 'Manage FAQ'
    ]
];
