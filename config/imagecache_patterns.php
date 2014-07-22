<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package   Modules
 * @category  Imagecache
 * @author    Vyacheslav Malchik <validoll-ru@yandex.ru>
 * @uses      Image Module
 * 
 * @example
 * return array(
 *    'thumb' => array(
 *       'width' => '50%',
 *       'height' => '50px',
 *       'crop' => TRUE,
 *       'quality' => 80,
 *       'watermark' => 'custom_watermark',
 *    ),
 * );
 */
return array(
    'thumb' => array(
        'width' => '80',
        'height' => '80',
        'crop' => TRUE,
    ),

);
