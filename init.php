<?php defined('SYSPATH') or die('No direct script access.');

$config = Kohana::$config->load('imagefly');
$cachedir = trim($config->get('cache_dir'), '/');

// Catch-all route for ImageCache
Route::set('imagecache', $cachedir.'(/<pattern>(/<imagepath>))', array('pattern' => '[^\/]+', 'imagepath' => '.+\..+'))
	->defaults(array(
		'controller' => 'Imagecache',
		'action' => 'index',
            ));
