<?php

return [
	
	/**
     * The version constraint.
     */
    'version' => '0.2',

    /**
     * The asset path.
     */
    'assets' => '/resources/assets/',
    
    /**
     * The tables to manage.
     */
    'tables' => [
    ],
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
     * The styles and scripts to auto-load.
     */
    'enqueue' => [
        __DIR__ . '/app/enqueue.php'
    ],
];