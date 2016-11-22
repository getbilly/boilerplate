<?php

namespace MyPlugin;

$action->add([
	'type'		=> 'admin',
	'method'	=> 'admin_menu',
	'uses'		=> [__NAMESPACE__ . '\Controllers\AdminController', 'menu']
]);

$action->boot();