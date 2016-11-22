<?php

namespace MyPlugin;

$action->add([
	'type'		=> 'admin',
	'method'	=> 'admin_menu',
	'uses'		=> [__NAMESPACE__ . '\Controllers\AdminController', 'menu']
]);

/* Can we clean this up later? */
$action->boot();

return View::render('@MyPlugin/example.twig', [
	'title' => 'TITLE'
]);