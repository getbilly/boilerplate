<?php

if ( ! file_exists(__DIR__ . '/globals.php'))
    throw new Exception("Globals file not found.");

require __DIR__ . '/globals.php';

$root = base_directory();

$plugin = $root . 'plugin.php';

$billy = new Billy\Framework\Application();

$config = $billy->getPluginConfig($root);

register_activation_hook($plugin, function () use ($billy, $config, $root)
{
	$billy->loadPlugin($config);
	$billy->activatePlugin($root);
});

register_deactivation_hook($plugin, function () use ($billy, $root)
{
	$billy->deactivatePlugin($root);
});

$billy->loadPlugin($config);