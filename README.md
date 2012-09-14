# Imagefly

This module allows you to quickly create resized / cropped images directly through url parameters. Modified images are cached after the initial request and served up thereafter to help reduce server strain.

## Demo

[Click here to see the Imagefly demo.](http://www.fkportfolio.com/playground/imagefly-demo)

## Getting started

In your `application/bootstrap.php` file modify the call to Kohana::modules and include the image and imagefly modules.

    Kohana::modules(array(
        ...
        'image'    => MODPATH.'image',
        'imagefly' => MODPATH.'imagefly',
        ...
    ));

[!!] The image module is requried for the Imagefly module to work.

## Notes

* Imagefly will not process images when the width and height prams are the same as the source
* Images are scaled up if the supplied width or height params are lager then the source width or height 
* Don't forget to make your cache directory writable.
* Inspired by the [smart-lencioni-image-resizer](http://code.google.com/p/smart-lencioni-image-resizer/) by Joe Lencioni

## Compatibility

Imagefly currently works with Kohana 3.2 only.

For Kohana 3.0.x and 3.1.x users, a no longer maintained version can be found [here](http://code.google.com/p/kohana-3-imagefly/)

## Configuration

The default config file is located in `MODPATH/imagefly/config/imagefly.php`.  You should copy this file to `APPPATH/config/imagefly.php` and make changes there, in keeping with the cascading filesystem.

The Imagefly configuration file looks like this:

    array(
        'cache_expire'     => string CACHE_EXPIRE,
        'cache_dir'        => string CACHE_DIR,
        'mimic_source_dir' => string MIMIC_SOURCE_DIR,
        'enforce_presets'  => TRUE,
        'scale_up'		   =>  FALSE,
        'presets'          => array(
            'w320-h240-c',
        ),
    ),
	
Understanding each of these settings is important.

**CACHE_EXPIRE**  
How long before the browser checks the server for a new version of the modified image. Default is 1 week (7 * 24 * 60 * 60)

**CACHE_DIR**  
Path to the image cache directory you would like to use, don't forget the trailing slash!

**MIMIC_SOURCE_DIR**  
Mimic the source file folder structure within the cache directory. Useful if you want to keep track of cached files and folders to perhaps  periodically clear some cache folders but not others. Default TRUE

**SCALE_UP**  
If image should be scaled up beyond it's original dimensions on resize. Default FALSE.

**ENFORCE_PRESETS**  
Will only allow param configurations entered in the PRESETS array. Recommended for production sites to reduce the impact of spamming different sized images on the server.

**PRESETS**  
Imagefly params to allow when ENFORCE_PRESETS is set to TRUE.

## Usage Examples

Here are some examples of what you can do with Imagefly.

**Resize to exactly 100px width and height cropping from the center**  
`/imagefly/w100-c/path/to/image.jpg`  
**OR**  
`/imagefly/h100-c/path/to/image.jpg`

**Resize to exactly 100px width and 150px height cropping from the center**  
`/imagefly/w100-h150-c/path/to/image.jpg`

**Resize proportionally until width is 100 pixels**  
`/imagefly/w100/path/to/image.jpg`

**Resize proportionally until height is 100 pixels**  
`/imagefly/h100/path/to/image.jpg`

**Resize proportionally until either the width or height is 100 pixels, whichever comes first**  
`/imagefly/w100-h100/path/to/image.jpg`

**Resize proportionally until height is 100 pixels with JPEG quality set to 60**  
`/imagefly/h100-q60/path/to/image.jpg`