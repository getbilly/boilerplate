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
     * Deactivate
     */
    'actions' => [
        __DIR__ . '/app/action.php'
    ],

    /**
     * The styles and scripts to auto-load.
     */
    'enqueue' => [
        __DIR__ . '/app/enqueue.php'
    ],
];