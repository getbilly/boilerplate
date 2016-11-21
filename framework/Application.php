<?php

namespace Billy\Framework;

use Illuminate\Container\Container;
use Billy\Framework\Database;
use Billy\Framework\Enqueue;
use Billy\Framework\Action;

class Application extends Container
{
	protected static $instance;
    
    protected $configurations = [];

	public function __construct()
	{		
		$this->registerBaseBindings();
        $this->registerDatabase();		
        $this->registerEnqueue();   
        $this->registerActions();
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
        $this->singleton(
            'database', 
            'Billy\Framework\Database'
        );

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

    protected function registerActions()
	{
		$this->instance( 
            'action',
            $this->make('Billy\Framework\Action')
        );
		
        $this->alias( 
            'action', 
            'Billy\Framework\Action' 
        );
	}

	public function loadPlugin($config)
	{
		$this->loadPluginX(
            'enqueue',
            array_get($config, 'enqueue', [])
        );   

        $this->loadPluginX(
            'action',
            array_get($config, 'actions', [])
        );  
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
                'actions'
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
                'actions'
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