<?php 

namespace Billy\Framework;

class Action
{
	protected $namespace = null;
	
	public function __construct() {

	}

	/**
     * Sets the current namespace.
     *
     * @param  string $namespace
     * @return void
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }
    /**
     * Unsets the current namespace.
     *
     * @return void
     */
    public function unsetNamespace()
    {
        $this->namespace = null;
    }

    /**
     * Return the correct callable based on action
     *
     * @param  array   $panel
     * @param  boolean $strict
     * @return void
     */
    protected function handler($panel, $strict = false)
    {
        $callable = $uses = $panel['uses'];
        $method = strtolower($this->http->method());
        $action = strtolower($this->http->get('action', 'uses'));
       
		if ($callable === $uses || is_array($callable)) {
            $callable = array_get($panel, $action, false) ?: $callable;
        }
		
        if ($callable === $uses || is_array($callable)) {
            $callable = array_get($panel, "{$method}.{$action}", false) ?: $callable;
        }
        
		if (is_array($callable)) {
            $callable = $uses;
        }

        if ($strict && $uses === $callable) {
            return false;
        }
        
        return true;
    }
}