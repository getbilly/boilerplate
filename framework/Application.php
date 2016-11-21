<?php

namespace Billy\Framework;

use Illuminate\Container\Container;
use Billy\Framework\Database;
use Billy\Framework\Enqueue;
use Billy\Framework\Route;

class Application extends Container
{
	protected static $instance;

	/**
     * The plugin configurations.
     *
     * @var array
     */
    protected $configurations = [];


	public function __construct()
	{		
        
		$this->registerBaseBindings();
        
		$this->singleton(
            'database', 
            'Billy\Framework\Database'
        );
        $this->registerDatabase();		

        $this->singleton(
            'enqueue', 
            'Billy\Framework\Enqueue'
        );
        $this->registerEnqueue();

        $this->registerRoutes();
     
	}

	/**
     * Register the basic bindings into the container.
     * @return void
     */
    protected function registerBaseBindings()
    {
        static::setInstance($this);

        $this->instance(
            'app', 
            $this
        );

        $this->instance(
            'Illuminate\Container\Container', 
            $this
        );
    }

	protected function registerDatabase()
	{
		$this->bind(
            'database', 
            $this['database']
        );
	}

	protected function registerEnqueue()
	{
		$this->instance( 
            'enqueue',
            $this->make('Billy\Framework\Enqueue')
        );
		
        $this->alias( 
            'enqueue', 
            'Billy\Framework\Enqueue' 
        );
	}

    public function registerRoutes()
    {
        $this->app->instance(
            'route',
            $this->app->make('Billy\Framework\Route', ['app' => $this->app])
        );
        
        $this->app->alias(
            'route',
            'Billy\Framework\Route'
        );
    }

	public function loadPlugin($config)
	{
        $this->loadPluginRoutes(
            'route',
            array_get($config, 'routes', [])
        );

		$this->loadPluginX(
            'enqueue',
            array_get($config, 'enqueue', [])
        );   
	}

    /**
     * Load all a plugin's routes.
     *
     * @param array $routes
     * @return void
     */
    protected function loadPluginRoutes($x, $routes = [])
    {
        $container = $this;
        $route = $this['route'];

        foreach ($routes as $namespace => $requires) {
            
            $route->setNamespace($namespace);

            foreach ((array) $requires as $require) {
                @require_once "$require";
            }

            $route->unsetNamespace();
        }
    }

  	/**
     * Load all a plugin's :x.
     *
     * @param array $requires
     * @return void
     */
    protected function loadPluginX($x, $requires = [])
    {
        $container = $this;
        $$x = $this[$x];

        foreach ($requires as $require) {
            @require_once "$require";
        }
    }

	public function activatePlugin($root)
	{
		# get the plugin file
		$plugin = $root . 'plugin.php';

		$config = $this->getPluginConfig($root);
	
 		foreach (array_get($config, 'activators', []) as $activator) {
			 
            if ( ! file_exists($activator)) {
                continue;
            }

			$this->loadWith($activator, [
				'enqueue',
			]);
		}

	}

    public function deactivatePlugin($root)
	{
		# get the plugin file
		$plugin = $root . 'plugin.php';

		$config = $this->getPluginConfig($root);
	
 		foreach (array_get($config, 'dectivators', []) as $deactivator) {
			 
            if ( ! file_exists($deactivator)) {
                continue;
            }

			$this->loadWith($deactivator, [
				'enqueue',
			]);
		}
	}

	/**
     * Loads a file with variables in scope.
     *
     * @param  string $file
     * @param  array  $refs
     * @return void
     */
    protected function loadWith($file, $refs = [])
    {
        $container = $this;

        foreach ($refs as $ref) {
            $$ref = $this[$ref];
        }

        @require $file;
    }

	/**
     * Gets a plugin's configuration.
     *
     * @param  string $root
     * @return array
     */
    public function getPluginConfig($root)
    {
 		if ( ! isset($this->configurations[$root])) {
            $this->configurations[$root] = @require_once "$root/plugin.config.php" ?: [];
        }
        return $this->configurations[$root];
    }

    public function bootControllers()
    {
        // $directory = base_directory() . 'app/Controllers';
        // $directory = new \RecursiveDirectoryIterator($directory);
        // $iterator = new \RecursiveIteratorIterator($directory);
        // $regex = new \RegexIterator($iterator, '/^.+\Controller.php$/i', \RecursiveRegexIterator::GET_MATCH);

        // foreach ( $regex as $info ) {
        //     var_dump($info);
        // }

        // die();
    }
}