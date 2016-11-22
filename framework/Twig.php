<?php 

namespace Billy\Framework;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_SimpleFunction;

class Twig
{

	protected static $instance;
	public static $template;

	public static $loader;
	public static $twig; 

	/**
	 * Initialize the plugin.
	 *
	 * @since     1.0.0
	 */
	public function __construct()
	{
		
	}

	public function constructTwig()
	{
		$directory = base_directory() . 'resources/views/';

		self::$loader = new Twig_Loader_Filesystem($directory);

		self::$twig = new Twig_Environment(self::$loader, self::environment());

		if (defined('WP_DEBUG') && true === WP_DEBUG) {
			self::$twig->addExtension(new Twig_Extension_Debug());
		}

		foreach(self::variables() as $key => $value) {
			self::$twig->addGlobal($key, $value);	
		}

		foreach (self::functions() as $function) {
            self::$twig->addFunction(new Twig_SimpleFunction($function, $function));
        }
	}


	protected function environment()
	{
		$envSettings = [
			'charset' 			=> 'utf-8',
			'auto_reload' 		=> true,
            'strict_variables' 	=> false,
            'autoescape' 		=> true,
			'cache' 			=> content_directory() . '/twig_cache',
			'debug'				=> true
		];

		if (defined('WP_DEBUG') && true === WP_DEBUG) {
			$envSettings['debug'] = true;
		}

		return $envSettings;
	}

	protected function variables()
	{
		# Here we set some default global variables
		$variables = [
			'site' => [
				'lang_attributes' 		=> get_bloginfo('language'),
				'charset' 				=> get_bloginfo('charset'),
				'url' 					=> get_bloginfo('url'),
				'stylesheet_directory' 	=> get_stylesheet_directory_uri(),
				'title' 				=> get_bloginfo('name'),
				'description' 			=> get_bloginfo('description')
			]
		];

		return $variables;
	}

	protected function functions()
	{
		$functions = [
			'dd',
			'wp_head',
			'wp_footer',
			'wp_title',
			'body_class',
			'wp_nav_menu'
		];

		return $functions;
	}

	/**
	 * A wrapper function for rendering templates
	 *
	 * @param   string   $template      The name of the template that is to be rendered
	 * @param   array    $vals          An array of variables that are to be rendered with the template
	 * @since 1.0.0
	 */
	public function render($template, $vals, $echo = true)
	{
		self::constructTwig();

		# Check whether we are echoing or returning
		if (true === $echo) {
			echo self::$twig->render($template, $vals);
		} else {
			return self::$twig->render($template, $vals);
		}
	}

	/**
	 * Return an instance of this class.
	 * @return    object    A single instance of this class.
	 */
	public static function instance()
	{
		# If the single instance hasn't been set, set it now.
		if (null === self::$instance) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}