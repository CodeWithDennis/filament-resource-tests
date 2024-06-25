<?php

return [
    /**
     * The directory where the tests will be generated in.
     */
    'directory_name' => env('FILAMENT_TESTS_DIRECTORY_NAME', 'tests/Feature'),

    /**
     * Whether to separate the tests into folders based on the resource name.
     */
    'separate_tests_into_folders' => env('FILAMENT_TESTS_SEPARATE_TESTS_INTO_FOLDERS', false),

    /**
     * Customize the tests to be generated.
     */
    'generate' => [
        'resource' => [
            'page' => [
                'create' => [
                    'render' => true,

                    'action' => [
                        'render' => true,
                    ],

                    'form' => [
                        'field' => [
                            'disabled' => true,
                            'exists' => true,
                            'hidden' => true,
                            'validate' => true,
                        ],

                        'exists' => true,
                        'render' => true,
                        'validate' => true,
                    ],

                    'widget' => [
                        'render_footer_widgets' => true,
                        'render_header_widgets' => true,
                    ],

                ],

                'custom' => [], // TODO: Implement

                'edit' => [
                    'render' => true,

                    'action' => [
                        'render' => true,
                    ],

                    'form' => [
                        'field' => [
                            'disabled' => true,
                            'exists' => true,
                            'hidden' => true,
                            'validate' => true,
                        ],

                        'exists' => true,
                        'fill' => true,
                        'render' => true,
                        'validate' => true,
                    ],

                    'relation_manager' => [
                        'header_action' => [
                            'exist' => true,
                            'hidden' => true,
                            'visible' => true,
                        ],

                        'table' => [
                            'action' => [
                                'delete' => true,
                                'delete_force' => true,
                                'delete_soft' => true,
                                'exist' => true,
                                'hidden' => true,
                                'replicate' => true,
                                'restore' => true,
                                'url' => true,
                                'url_tab' => true,
                                'visible' => true,
                            ],

                            'bulk_action' => [
                                'delete' => true,
                                'delete_force' => true,
                                'delete_soft' => true,
                                'exist' => true,
                                'restore' => true,
                            ],

                            'column' => [
                                'cannot_render' => true,
                                'description_above' => true,
                                'description_below' => true,
                                'exist' => true,
                                'extra_attributes' => true,
                                'render' => true,
                                'search' => true,
                                'search_individually' => true,
                                'select' => true,
                                'sort' => true,
                            ],

                            'filter' => [
                                'exist' => true,
                                'hidden' => true,
                                'visible' => true,
                            ],

                            'summary' => [
                                'average' => true,
                                'count' => true,
                                'count_icon' => true,
                                'date_range' => true,
                                'range' => true,
                                'sum' => true,
                            ],

                            'description' => true,
                            'heading' => true,
                        ],

                        'list_records' => true,
                        'list_records_paginated' => true,
                        'render' => true,
                        'trashed' => true,
                    ],

                    'widget' => [
                        'render_footer_widgets' => true,
                        'render_header_widgets' => true,
                    ],

                ],

                'index' => [
                    'action' => [
                        'exist' => true,
                        'hidden' => true,
                        'visible' => true,
                    ],

                    'table' => [
                        'action' => [
                            'delete' => true,
                            'delete_force' => true,
                            'delete_soft' => true,
                            'exist' => true,
                            'replicate' => true,
                            'restore' => true,
                            'url' => true,
                            'url_tab' => true,
                        ],

                        'bulk_action' => [
                            'delete' => true,
                            'delete_force' => true,
                            'delete_soft' => true,
                            'exist' => true,
                            'restore' => true,
                        ],

                        'column' => [
                            'cannot_render' => true,
                            'description_above' => true,
                            'description_below' => true,
                            'exist' => true,
                            'extra_attributes' => true,
                            'render' => true,
                            'search' => true,
                            'search_individually' => true,
                            'select' => true,
                            'sort' => true,
                        ],

                        'filter' => [
                            'add' => true,
                            'remove' => true,
                            'reset' => true,
                        ],

                        'summary' => [
                            'average' => true,
                            'count' => true,
                            'count_icon' => true,
                            'date_range' => true,
                            'range' => true,
                            'sum' => true,
                        ],

                        'description' => true,
                        'heading' => true,
                    ],

                    'widget' => [
                        'render_footer_widgets' => true,
                        'render_header_widgets' => true,
                    ],

                    'list_records' => true,
                    'list_records_paginated' => true,
                    'render' => true,
                    'trashed' => true,
                ],

                'view' => [
                    'render' => true,

                    'action' => [
                        'render' => true,
                    ],

                    'form' => [
                        'render' => true,
                    ],

                    'infolist' => [
                        'action' => [
                            'exist' => true,
                        ],

                        'entry' => [
                            'render' => true,
                        ],

                        'render' => true,
                    ],

                    'relation_manager' => [
                        'header_action' => [
                            'exist' => true,
                            'hidden' => true,
                            'visible' => true,
                        ],

                        'table' => [
                            'action' => [
                                'delete' => true,
                                'delete_force' => true,
                                'delete_soft' => true,
                                'exist' => true,
                                'hidden' => true,
                                'replicate' => true,
                                'restore' => true,
                                'url' => true,
                                'url_tab' => true,
                                'visible' => true,
                            ],

                            'bulk_action' => [
                                'delete' => true,
                                'delete_force' => true,
                                'delete_soft' => true,
                                'exist' => true,
                                'restore' => true,
                            ],

                            'column' => [
                                'cannot_render' => true,
                                'description_above' => true,
                                'description_below' => true,
                                'exist' => true,
                                'extra_attributes' => true,
                                'render' => true,
                                'search' => true,
                                'search_individually' => true,
                                'select' => true,
                                'sort' => true,
                            ],

                            'filter' => [
                                'add' => true,
                                'remove' => true,
                                'reset' => true,
                            ],

                            'summary' => [
                                'average' => true,
                                'count' => true,
                                'count_icon' => true,
                                'date_range' => true,
                                'range' => true,
                                'sum' => true,
                            ],

                            'description' => true,
                            'heading' => true,
                        ],

                        'list_records' => true,
                        'list_records_paginated' => true,
                        'render' => true,
                        'trashed' => true,
                    ],

                    'widget' => [
                        'render_footer_widgets' => true,
                        'render_header_widgets' => true,
                    ],
                ],
            ],
        ],
    ],

    /**
     * Run laravel/pint for the generated tests after they are generated.
     */
    'run_pint_after_generation' => env('FILAMENT_TESTS_RUN_PINT_AFTER_GENERATION', true),
];
