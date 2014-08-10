# Imagefly

This module allows you to quickly create resized / cropped images directly through url parameters.  
Modified images are cached after the initial request and served up thereafter to help reduce server strain.

## Demo

[Click here to see Imagefly in action.](http://www.fkportfolio.com/playground/imagefly-demo)

## Compatibility

Imagefly currently works with Kohana 3.2 and 3.3

## Getting Started

In your `application/bootstrap.php` file modify the call to Kohana::modules and include the image and imagefly modules.

    Kohana::modules(array(
        ...
        'image'    => MODPATH.'image',
        'imagefly' => MODPATH.'imagefly',
        ...
    ));

The image module is requried for the Imagefly module to work.

## Configuration

The default config file is located in `MODPATH/imagefly/config/imagefly.php`  
You should copy this file to `APPPATH/config/imagefly.php` and make changes there, in keeping with the cascading filesystem.

## Configuration Options

**cache_expire:** 604800  
Number of seconds before the browser checks the server for a new version of the modified image.

**cache_dir:** 'cache/'  
Path to the image cache directory you would like to use, don't forget the trailing slash!

**source_dir:** `''`  
Path prefix to the source directory you would like to use, don't forget the trailing slash!

**mimic_source_dir:** TRUE  
Mimic the source file folder structure within the cache directory.  
Useful if you want to keep track of cached files and folders to perhaps periodically clear some cache folders but not others.

**quality:** 80  
The default quality of images when not specified in the URL.

**scale_up:** FALSE  
If the image should be scaled up beyond it's original dimensions on resize.

**enforce_presets:** FALSE  
Will only allow param configurations set in the `presets`  
Best enabled on production sites to reduce spamming of different sized images on the server.

**presets**  
Imagefly params that are allowed when `enforce_presets` is set to `TRUE`  
Any other param configuration will throw a 404 error.
    
    // Example presets
    'presets' => array(
        'w320-h240-c',
        'w640-w480-q60'
    )
    
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

## Usage Examples

Here are some examples of what you can do with Imagefly.

**Resize to exactly 100px width and height cropping from the center**  
`<img src="/imagefly/w100-c/path/to/image.jpg">`  **OR**  `<img src="/imagefly/h100-c/path/to/image.jpg">`

**Resize to exactly 100px width and 150px height cropping from the center**  
`<img src="/imagefly/w100-h150-c/path/to/image.jpg">`

**Resize proportionally until width is 100 pixels**  
`<img src="/imagefly/w100/path/to/image.jpg">`

**Resize proportionally until height is 100 pixels**  
`<img src="/imagefly/h100/path/to/image.jpg">`

**Resize proportionally until either the width or height is 100 pixels, whichever comes first**  
`<img src="/imagefly/w100-h100/path/to/image.jpg">`

**Resize proportionally until height is 100 pixels with JPEG quality set to 60**  
`<img src="/imagefly/h100-q60/path/to/image.jpg">`

**Adding a watermark**  
`<img src="/imagefly/w600-first_watermark/path/to/image.jpg">`  

**Adding multiple watermarks**  
`<img src="/imagefly/w600-first_watermark-second_watermark/path/to/image.jpg">`  

## Notes

* Imagefly will not process images when the width and height prams are the same as the source
* Don't forget to make your cache directory writable.
* Inspired by the [smart-lencioni-image-resizer](http://code.google.com/p/smart-lencioni-image-resizer/) by Joe Lencioni