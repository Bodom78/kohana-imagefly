## Configuration

The default config file is located in `MODPATH/imagefly/config/imagefly.php`  
You should copy this file to `APPPATH/config/imagefly.php` and make changes there, in keeping with the cascading filesystem.

## Configuration Options

Empty or undefined options will always fallack to their defaults.

**cache_expire:** 604800  
Number of seconds before the browser checks the server for a new version of the modified image.

**cache_dir:** 'cache/'  
Path to the image cache directory you would like to use, don't forget the trailing slash!

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