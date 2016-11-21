<?php 

namespace Billy\Framework;

use Exception;
use Illuminate\Contracts\Support\Jsonable;
use InvalidArgumentException;
use JsonSerializable;

class Route {

	/**
     * @var array
     */
    protected static $methods = [
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE'
    ];

	/**
     * @var array
     */
    protected static $wpPanels = [
        'index.php',
		'edit.php', 
		'upload.php',
        'link-manager.php', 
		'edit.php?post_type=*',
        'edit-comments.php', 
		'themes.php',
        'plugins.php', 
		'users.php', 
		'tools.php',
        'options-general.php', 
		'settings.php'
    ];

	/**
     * @var array
     */
    protected $adminRoutes = [];

	 /**
     * The current namespace.
     * @var string|null
     */
    protected $namespace = null;

	/**
     * @var \Billy\Framework\Application
     */
    protected $app;

	
	public function __construct(Application $app, Http $http)
    {
        $this->app = $app;
        $this->http = $http;

		add_action('admin_menu', [$this, 'bootAdmin']);
	}

	public function bootAdmin()
	{
		foreach ($this->adminRoutes as $route) {
            switch ($route['type']) {
                case 'page':
                    $this->addRoute($route);
                    break;
                case 'subpage':
                    $this->addSubPage($route);
                    break;
            }
        }
	}

	/**
     * Adds a panel.
     *
     * @param array $data
     * @param $uses
     */
    public function admin($data, $uses = null)
    {
        if (!is_null($uses)){
            $data['uses'] = $uses;
        }

        foreach (['type', 'uses', 'title', 'slug'] as $key) {
            if (isset($data[$key])) {
                continue;
            }
            throw new InvalidArgumentException("Missing {$key} definition for panel");
        }

        if (!in_array($data['type'], ['page', 'subpage'])) {
            throw new InvalidArgumentException("Unknown panel type '{$data['type']}'");
        }

        if (in_array($data['type'], ['subpage']) && !isset($data['parent'])) {
            throw new InvalidArgumentException("Missing parent definition for sub-panel");
        }
        if ($data['type'] === 'subpage') {
            $arr = array_filter(static::$wpPanels, function ($value) use ($data) {
                return str_is($value, $data['parent']);
            });
            if (count($arr) === 0) {
                throw new InvalidArgumentException("Unknown WP panel '{$data['parent']}'");
            }
        }
        if (isset($data['as'])) {
            $data['as'] = $this->namespaceAs($data['as']);
        }

        if ($data['type'] === 'sub-panel' && isset($data['parent'])) {
            $data['parent'] = $this->namespaceAs($data['parent']);
        }

        $this->adminRoutes[] = $data;
    }

	/**
     * Adds a route.
     * @param $route
     * @return void
     */
    protected function addRoute($route)
    {
        add_menu_page(
            $route['title'],
            $route['title'],
            isset($route['capability']) && $route['capability'] ? $route['capability'] : 'manage_options',
            $route['slug'],
            $this->makeCallable($route),
            isset($route['icon']) ? $this->fetchIcon($route['icon']) : '',
            isset($route['order']) ? $route['order'] : null
        );

        if (isset($route['rename']) && !empty($route['rename'])) {
            $this->addSubPage([
                'title'  => $route['rename'],
                'rename' => true,
                'slug'   => $route['slug'],
                'parent' => $route['slug']
            ]);
        }
    }

	/**
     * Adds a subpage.
     *
     * @param $route
     * @return void
     */
    protected function addSubPanel($route)
    {
        foreach ($this->adminRoutes as $parent) {
            if (array_get($parent, 'as') !== $this->namespaceAs($route['parent'])) {
                continue;
            }

            $route['parent'] = $parent['slug'];
        }

        add_submenu_page(
            $route['parent'],
            $route['title'],
            $route['title'],
            isset($route['capability']) && $route['capability'] ? $route['capability'] : 'manage_options',
            $route['slug'],
            isset($route['rename']) && $route['rename'] ? null : $this->makeCallable($route)
        );
    }

	/**
     * Fetches an icon for a panel.
     *
     * @param $icon
     * @return string
     */
    protected function fetchIcon($icon)
    {
        if (empty($icon)) {
            return '';
        }
        
		if (substr($icon, 0, 9) === 'dashicons' || substr($icon, 0, 5) === 'data:'
            || substr($icon, 0, 2) === '//' || $icon == 'none') {     
			return $icon;
        }

        return $icon;
    }

	/**
     * Makes a callable for the panel hook.
     *
     * @param $route
     * @return callable
     */
    protected function makeCallable($route)
    {
        return function () use ($route) {
            return $this->handler($route);
        };
    }

	/**
     * Calls the routes's callable.
     * @param $callable
     * @return void
     */
    protected function call($callable)
    {
        $response = $this->app->call(
            $callable,
            ['app' => $this->app]
        );

        if ($response instanceof RedirectResponse) {
            $response->flash();
        }

        if ($response instanceof Response) {
            status_header($response->getStatusCode());
            
			foreach ($response->getHeaders() as $key => $value) {
                @header($key . ': ' . $value);
            }

            echo $response->getBody();
            return;
        }

        if (is_null($response) || is_string($response)) {
            echo $response;
            return;
        }

        if (is_array($response) || $response instanceof Jsonable || $response instanceof JsonSerializable) {
            echo (new JsonResponse($response))->getBody();
            return;
        }

        throw new Exception('Unknown response type!');
    }

	/**
     * Gets a panel.
     *
     * @param  string  $name
     * @param  boolean $slug
     * @return array
     */
    protected function getAdminRoute($name, $slug = false)
    {
        $slug = $slug ? 'slug' : 'as';

        foreach ($this->adminRoutes as $route) {
            if (array_get($route, $slug) !== $name) {
                continue;
            }

            return $route;
        }
        return null;
    }

    /**
     * Gets the panels.
     *
     * @return array
     */
    public function getAdminRoutes()
    {
        return array_values($this->adminRoutes);
    }

	/**
     * Get the URL to a admin route.
     *
     * @param  string $name
     * @return string
     */
    public function url($name)
    {
        if (($adminRoute = $this->getAdminRoute($name)) === null) {
            return null;
        }
        $slug = array_get($adminRoute, 'slug');

        if (array_get($adminRoute, 'type') === 'subpage') {
            return admin_url(add_query_arg('page', $slug, array_get($adminRoute, 'parent')));
        }
        return admin_url('admin.php?page=' . $slug);
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
     * Namespaces a name.
     *
     * @param  string $as
     * @return string
     */
    protected function namespaceAs($as)
    {
        if ($this->namespace === null) {
            return $as;
        }

        return $this->namespace . '::' . $as;
    }

    /**
     * Return the correct callable based on action
     *
     * @param  array   $route
     * @param  boolean $strict
     * @return void
     */
    protected function handler($route, $strict = false)
    {
        $callable = $uses = $route['uses'];
        $method = strtolower($this->http->method());
        $action = strtolower($this->http->get('action', 'uses'));
        $callable = array_get($route, $method, false) ?: $callable;
       
	    if ($callable === $uses || is_array($callable)) {
            $callable = array_get($route, $action, false) ?: $callable;
        }
        
		if ($callable === $uses || is_array($callable)) {
            $callable = array_get($route, "{$method}.{$action}", false) ?: $callable;
        }

        if (is_array($callable)) {
            $callable = $uses;
        }

        if ($strict && $uses === $callable) {
            return false;
        }

        try {
            $this->call($callable);
        } catch (HttpErrorException $e) {
            if ($e->getStatus() === 301 || $e->getStatus() === 302) {
                $this->call(function () use (&$e) {
                    return $e->getResponse();
                });
            }
            global $wp_query;
            $wp_query->set_404();
            status_header($e->getStatus());
        }
        return true;
    }

   	/**
     * Flushes Wordpress werite rules
     */
    public function flushRules()
    {
        flush_rewrite_rules();
    }
}