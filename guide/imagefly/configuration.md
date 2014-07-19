## Configuration

The default config files is located in `MODPATH/imagecache/config/*`.
 - Main configuration - `MODPATH/imagecache/config/imagecache.php`
 - Configuration patterns for modify images - `MODPATH/imagecache/config/imagecache_patterns.php`

You should copy this files to `APPPATH/config/*` and make changes there, in keeping with the cascading filesystem.

## Configuration Options (imagecache.php)

Empty or undefined options will always fallack to their defaults.

**cache_expire:** 604800
Number of seconds before the browser checks the server for a new version of the modified image.

**cache_dir:** 'cache/'
Path to the image cache directory you would like to use, don't forget the trailing slash!

**quality:** 80  
The default quality of images when not specified in the URL.

**scale_up:** FALSE
If the image should be scaled up beyond it's original dimensions on resize.

**default_image** FALSE
Default image if the requested image is not available.
For example:

    'default_image'    => 'files/misc/default.png',

**watermarks**  
Configure one or more watermarks. Each configuration key can be passed as a param through an Imagefly URL to apply the watermark.

If no offset is specified, the center of the axis will be used.  
If an offset of TRUE is specified, the bottom of the axis will be used.

    // Example watermarks
    'watermarks' => array(
        'first_watermark' => array(
            'image'    => 'path/to/first/watermark.png',
            'offset_x' => TRUE,
            'offset_y' => TRUE,
            'opacity'  => 80
        ),
        'second_watermark' => array(
            'image'    => 'path/to/second/watermark.png',
            'offset_x' => 5,
            'offset_y' => 5,
            'opacity'  => 50
        )
    )

## Patterns configuration (imagecache_patterns.php)

Example of pattern settings:

    'thumb' => array(
       'width' => '50%',
       'height' => '50px',
       'crop' => TRUE,
       'quality' => 80,
       'watermark' => 'custom_watermark',
    ),

**width**
Width of cached image. Allow 'px' or '%' suffix.

**height**
Height of cached image. Allow 'px' or '%' suffix.

**crop**
Use crop for cached image. If 'FALSE', then use scale.

**quality**
Quality of cached image.

**watermark**
Name of one of watermark from the main config file.
