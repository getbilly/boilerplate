<?php

$action->add([
	'type'		=> 'admin',
	'method'	=> 'init',
	'uses'		=> __NAMESPACE__ . '\Controllers\AdminController@menu'
]);
