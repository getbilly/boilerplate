<?php

namespace Billy\Framework;

use Illuminate\Container\Container;
use Billy\Framework\Database;
use Billy\Framework\Enqueue;

class Application extends Container
{
	protected static $instance;

	public function __construct()
	{		
		$this->registerBaseBindings();

        // $this->singleton('database', function() {
        //     return new Database();
        // });
        
		$this->singleton('database', 'Billy\Framework\Database');
        $this->registerDatabase();		

        $this->singleton('enqueue', 'Billy\Framework\Enqueue');

        $this->registerEnqueue();
	}

	/**
     * Register the basic bindings into the container.
     * @return void
     */
    protected function registerBaseBindings()
    {
        static::setInstance($this);

        $this->instance('app', $this);
        $this->instance('Illuminate\Container\Container', $this);
    }

	protected function registerDatabase()
	{
		$this->bind('database', $this['database']);
	}

	protected function registerEnqueue()
	{
		$this->bind('enqueue', $this['enqueue']);
	}
}