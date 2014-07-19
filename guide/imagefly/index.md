# ImageCache

This module allows you to quickly create resized / cropped images.
Modified images are cached after the initial request and served up thereafter to help reduce server strain.

## Compatibility

Imagecache currently works with Kohana 3.2 and 3.3

## Getting Started

In your `application/bootstrap.php` file modify the call to Kohana::modules and include the image and imagefly modules.

    Kohana::modules(array(
        ...
        'image'    => MODPATH.'image',
        'imagecache' => MODPATH.'imagefly',
        ...
    ));

[!!] The image module is requried for the ImageCache module to work.

## Notes

* This is progect based on [Imagefly](https://github.com/Bodom78/kohana-imagefly) by [Fady Khalife](https://github.com/Bodom78)
* ImageCache will not process images when the width and height prams are the same as the source
* Don't forget to make your cache directory writable.
* Inspired by the [smart-lencioni-image-resizer](http://code.google.com/p/smart-lencioni-image-resizer/) by Joe Lencioni
