<?php

/**
 * Ensure this is only ran once.
 */
if (defined('PlATE_AUTOLOAD')) return;
define('PlATE_AUTOLOAD', microtime(true));

@require 'globals.php';

// initiate db set up
new \Billy\Framework\Database();

