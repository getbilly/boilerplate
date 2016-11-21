<?php 

namespace MyPlugin;

/** @var \Billy\Framework\Enqueue $enqueue */

$enqueue->admin([
	'as' => 'plugin-admin',
	'src' => Helper::assetUrl('js/plugin-admin.js')
]);