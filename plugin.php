<?php
/**
 * @wordpress-plugin
 * Plugin Name: 	Billy Boilerplate
 * Plugin URI: 		https://getbilly.github.io/boilerplate
 * Description: 	Boilerplate for rapid plugin development. Built initially for Adtrak.
 * Version: 		0.1.0b
 * Author: 			Jack Whiting
 * Author URI: 		https://jackwhiting.co.uk
 * License: 		GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     billy
 */

# if this file is called directly, abort
if (! defined( 'WPINC' )) die;

if (!defined('JB_PLUGIN_PATH')) {
	define('JB_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
}

if (!defined('JB_PLUGIN_URL') ) {
	define('JB_PLUGIN_URL', plugin_dir_url( __FILE__ ));
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/getbilly/framework/bootstrap/autoload.php';
