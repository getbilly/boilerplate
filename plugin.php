<?php
/**
 * @wordpress-plugin
 * Plugin Name: 	JB Plate
 * Plugin URI: 		https://jackabox.github.io/plate
 * Description: 	Core functionality for WordPress Development.
 * Version: 		0.3.0
 * Author: 			Jack Whiting
 * Author URI: 		https://jackwhiting.co.uk
 * License: 		GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     boilerplate
 */

# if this file is called directly, abort
if (! defined( 'WPINC' )) die;

define('JB_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('JB_PLUGIN_url', plugin_dir_url( __FILE__ ));

/**
 * Vendor Autoloader
 */ 
require_once __DIR__ . '/vendor/autoload.php';

/**
 * JB Plugin Autoloader
 */ 
require_once __DIR__ . '/framework/bootstrap/autoload.php';
