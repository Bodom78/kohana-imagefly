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
