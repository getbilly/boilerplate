<?php 

namespace Billy\Framework;

use Exception;
use InvalidArgumentException;

class Action
{
	protected $app;
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
		if (!is_null($uses)) {
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
            throw new InvalidArgumentException("Missing {$key} definition for panel");
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

	protected function addPublicAction() 
	{
        if(!is_admin()) {
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

	/**
     * Makes a callable for the action hook.
     *
     * @param $action
     * @return callable
     */
    protected function makeCallable($action)
    {
        // return function () use ($action) {
        //     return $this->handler($action);
        // };
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
        // $callable = $uses = $panel['uses'];
        // $method = strtolower($this->http->method());
        // $action = strtolower($this->http->get('action', 'uses'));
        // $callable = array_get($panel, $method, false) ?: $callable;
        // if ($callable === $uses || is_array($callable))
        // {
        //     $callable = array_get($panel, $action, false) ?: $callable;
        // }
        // if ($callable === $uses || is_array($callable))
        // {
        //     $callable = array_get($panel, "{$method}.{$action}", false) ?: $callable;
        // }
        // if (is_array($callable))
        // {
        //     $callable = $uses;
        // }
        // if ($strict && $uses === $callable)
        // {
        //     return false;
        // }
        // try {
        //     $this->call($callable);
        // } catch (HttpErrorException $e) {
        //     if ($e->getStatus() === 301 || $e->getStatus() === 302)
        //     {
        //         $this->call(function () use (&$e)
        //         {
        //             return $e->getResponse();
        //         });
        //     }
        //     global $wp_query;
        //     $wp_query->set_404();
        //     status_header($e->getStatus());
        //     define('HERBERT_HTTP_ERROR_CODE', $e->getStatus());
        //     define('HERBERT_HTTP_ERROR_MESSAGE', $e->getMessage());
        //     Notifier::error('<strong>' . $e->getStatus() . '</strong>: ' . $e->getMessage());
        //     do_action('admin_notices');
        // }
        // return true;
    }
}