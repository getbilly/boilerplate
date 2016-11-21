<?php

use Billy\Framework\Application;

if ( ! file_exists(__DIR__ . '/globals.php'))
    throw new Exception("Globals file not found.");

require __DIR__ . '/globals.php';

$root = base_directory();

$plugin = $root . 'plugin.php';

$billy = new Application();

$config = $billy->getPluginConfig($root);

$billy->loadPlugin($config);

$billy->activatePlugin($root);