<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package   Modules
 * @category  Imagecache
 * @author    Vyacheslav Malchik <validoll-ru@yandex.ru>
 * @uses      Imagefly Module
 */

return array
(
    /**
     * Number of seconds before the browser checks the server for a new version of the modified image.
     */
    'cache_expire'     => 604800,
    /**
     * Path to the image cache directory you would like to use, don't forget the trailing slash!
     */
    'cache_dir'        => 'files/imagecache/',
    /**
     * The default quality of images when not specified in the URL
     */
    'quality'          => 80,
    /**
     * If the image should be scaled up beyond it's original dimensions on resize.
     */
    'scale_up'         => FALSE,
    /**
     * Default image if the requested image is not available.
     * 
     * @example 'default_image'    => 'files/misc/default.png',
     */
    'default_image'    => FALSE,
    /**
     * Configure one or more watermarks. Each configuration key can be passed as a param through an Imagefly URL to apply the watermark.
     * If no offset is specified, the center of the axis will be used.
     * If an offset of TRUE is specified, the bottom of the axis will be used.
     * 
     * @example
     * 'watermarks'       => array(
     *     'custom_watermark' => array(
     *         'image'    => 'path/to/watermark.png',
     *         'offset_x' => TRUE,
     *         'offset_y' => TRUE,
     *         'opacity'  => 80,
     *     ),
     * ),
     */
    'watermarks'       => array(

    ),
);
