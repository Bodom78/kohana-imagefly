<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @package   Modules
 * @category  Imagefly
 * @author    Fady Khalife
 * @uses      Image Module
 */

return array
(
	'cache_expire'     => 7 * 24 * 60 * 60,
	'cache_dir'        => 'media/imagecache/',
	'source_dir'       => MEDIAPATH.'images/',
	'quality'          => 95,
	'mimic_source_dir' => FALSE,
	'frame'            => FALSE,	
	'enforce_presets'  => FALSE,
	'presets'          => array(
		'w100-h240-c',
	),
);
