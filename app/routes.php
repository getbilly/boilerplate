<?php

namespace MyPlugin;

$route->admin([
    'type'   => 'page',
    'as'     => 'testPage',
    'title'  => 'My Plugin',
    'slug'   => 'myplugin-index',
    'uses'   => __NAMESPACE__ . '\Controllers\AdminController@index'
]);