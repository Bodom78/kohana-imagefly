# ImageCache

This module allows you to quickly create resized / cropped images.  
Modified images are cached after the initial request and served up thereafter to help reduce server strain,and also available, even when ImageCache switch off, on the same URL which was generated.

## Compatibility

ImageCache currently works with Kohana 3.2 and 3.3

## Getting Started

In your `application/bootstrap.php` file modify the call to Kohana::modules and include the image and imagecache modules.

    Kohana::modules(array(
        ...
        'image'    => MODPATH.'image',
        'imagecache' => MODPATH.'imagecache',
        ...
    ));

[!!] The image module is requried for the ImageCache module to work.

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

**default_image:** FALSE  
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

## Usage Examples

Here are some examples of what you can do with ImageCache.

At first set path of cache dir in your `imagecache.php`.
For example:

    'cache_dir' => 'files/imagecache/',

At second set at least one pattern in your `imagecache_patterns.php`.
For example:

    'thumb' => array(
        'width' => '80',
        'height' => '80',
        'crop' => TRUE,
    ),

**Use this path for get resized image**
`<img src="/files/imagecache/thumb/path/to/image.jpg">`

## Notes

* This is project based on [Imagefly](https://github.com/Bodom78/kohana-imagefly) by [Fady Khalife](https://github.com/Bodom78)
* ImageCache will not process images when the width and height prams are the same as the source
* Don't forget to make your cache directory writable.
* Inspired by the [smart-lencioni-image-resizer](http://code.google.com/p/smart-lencioni-image-resizer/) by Joe Lencioni
