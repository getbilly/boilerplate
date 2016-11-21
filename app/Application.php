<?php 

namespace MyPlugin;

class Application 
{
	public function __construct()
	{
		# root directory
		$root = base_directory();

		# get the plugin file
		$plugin = $root . 'plugin.php';

		$activator = new Activate;
		$deactivator = new Deactivate;

		register_activation_hook($plugin, [$activator, 'run']);
		register_deactivation_hook($plugin, [$deactivator, 'run']);
	}

	public static function run()
	{
		
	}
}