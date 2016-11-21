<?php 

namespace Billy\Framework;

use Exception;
use InvalidArgumentException;

class Action
{
    protected $actions = [];
	protected $namespace = null;

	public function __construct()
    {   
		$this->boot();
	}

	public function boot()
	{
		foreach ($this->actions as $action) {
			switch ($action['type']) {
				case 'admin':
					$this->addAdminAction($action);
					break;
				case 'public':
					$this->addPublicAction($action);
					break;
				default:
					break;
			}
		}
        
	}

	public function add($data, $type = null) 
	{
		if (!is_null($type)) {
			$data['type'] = $type;
		}

		if(!isset($data['priority'])) {
			$data['priority'] = 10;
		}

		if(!isset($data['args'])) {
			$data['args'] = 1;
		}

  		foreach (['method', 'uses'] as $key) {
            if (isset($data[$key])) {
                continue;
            }
            throw new InvalidArgumentException("Missing {$key} definition for action");
        }

		if (!in_array($data['type'], ['admin', 'public'])) {
            throw new InvalidArgumentException("Unknown action type '{$data['type']}'");
        }

		$this->actions[] = $data;
	}

	protected function addAdminAction($action)
	{
        if (is_admin()) {
            add_action(
                $action['method'],
                $action['uses'],
                $action['priority'],
                $action['args']
            );
        }
	}

	protected function addPublicAction($action) 
	{
        if(! is_admin()) {
            add_action(
                $action['method'],
                $action['uses'],
                $action['priority'],
                $action['args']
            );
        }
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
}