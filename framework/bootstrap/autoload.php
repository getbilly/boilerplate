<?php

use Billy\Framework\Application;

if ( ! file_exists(__DIR__ . '/globals.php'))
    throw new Exception("Globals file not found.");

require __DIR__ . '/globals.php';

$billy = new Application();