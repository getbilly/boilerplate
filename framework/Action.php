<?php 

namespace Billy\Framework;

use Exception;
use InvalidArgumentException;

class Action
{
	protected $namespace = null;

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

        switch ($data['type']) {
            case 'admin':
                $this->addAdminAction($data);
                break;
            case 'public':
                $this->addPublicAction($data);
                break;
            default:
                break;
        }
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