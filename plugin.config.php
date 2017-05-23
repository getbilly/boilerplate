<?php

return [

	/**
     * The version constraint.
     */
    'version' => '1.0',

    /**
     * The asset path.
     */
    'assets' => __DIR__ . '/resources/assets/',

    /**
     * Views
     */
    'views' => __DIR__ . '/resources/views',

	/**
     * Templates
     */
	'templates'	=> 'getbilly', // folder in child theme to load

    /**
     * Activate
     */
    'activators' => [
        __DIR__ . '/app/activate.php'
    ],

    /**
     * Deactivate
     */
    'deactivators' => [
        __DIR__ . '/app/deactivate.php'
    ],

    /**
     * Loader
     */
    'loader' => [
        __DIR__ . '/app/loader.php'
    ],

    /**
     * The styles and scripts to auto-load.
     */
    'enqueue' => [
        __DIR__ . '/app/enqueue.php'
    ]
];
