## Configuration

The default config file is located in `MODPATH/imagefly/config/imagefly.php`.  You should copy this file to `APPPATH/config/imagefly.php` and make changes there, in keeping with the [cascading filesystem](../kohana/files).

The Imagefly configuration file looks like this:

    array(
        'cache_expire'     => string CACHE_EXPIRE,
        'cache_dir'        => string CACHE_DIR,
        'mimic_source_dir' => string MIMIC_SOURCE_DIR,
        'enforce_presets'  => TRUE,
        'scale_up'		   =>  FALSE,
        'presets'          => array(
            'w320-h240-c',
            'w320-h240-c-q35',
        ),
    ),
	
Understanding each of these settings is important.

CACHE_EXPIRE
:  How long before the browser checks the server for a new version of the modified image. Default is 1 week (7 * 24 * 60 * 60)

CACHE_DIR
:  Path to the image cache directory you would like to use, don't forget the trailing slash!

MIMIC_SOURCE_DIR
:  Mimic the source file folder structure within the cache directory. Useful if you want to keep track of cached files and folders to perhaps  periodically clear some cache folders but not others. Default TRUE

ENFORCE_PRESETS
:  Will only allow param configurations entered in the PRESETS array. Recommended for production sites to reduce the impact of spamming different sized images on the server.

SCALE_UP
:  If image should be scaled up beyond it's original dimensions on resize. Default FALSE.

PRESETS
: Imagefly params to allow when ENFORCE_PRESETS is set to TRUE.