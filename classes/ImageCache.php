<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package   Modules
 * @category  Imagecache
 * @author    Vyacheslav Malchik <validoll-ru@yandex.ru>
 * @uses      Imagefly Module
 */
 
class ImageCache
{
    /**
     * @var  array       This modules config options
     */
    protected $config = NULL;

    /**
     * @var  array       Patterns config
     */
    protected $config_patterns = NULL;

    /**
     * @var  string      Stores the path to the cache directory which is either whats set in the config "cache_dir"
     *                   or processed sub directories when the "mimic_source_dir" config option id set to TRUE
     */
    protected $cache_dir = NULL;
    
    /**
     * @var  object      Kohana image instance
     */
    protected $image = NULL;
    
    /**
     * @var  boolean     A flag for weither we should serve the default or cached image
     */
    protected $serve_default = FALSE;

    /**
     * @var  string      Current pattern name
     */
    protected $pattern = NULL;

    /**
     * @var  string      The source filepath and filename
     */
    protected $source_file = NULL;
    
    /**
     * @var  array       Stores the URL params in the following format
     */
    protected $url_params = array(
        'w' => NULL,  // Width (int)
        'h' => NULL,  // Height (int)
        'c' => FALSE, // Crop (bool)
        'q' => NULL   // Quality (int)
    );
    
    /**
     * @var  string      Last modified Unix timestamp of the source file
     */
    protected $source_modified = NULL;
    
    /**
     * @var  string      The cached filename with path ($this->cache_dir)
     */
    protected $cached_file = NULL;

    /**
     * @var  string      The filename of default image
     */
    protected $default_image = NULL;

    /**
     * Constructorbot
     * 
     * @param string $imagepath Path of input image
     * @param string $pattern   Name of applied pattern
     */
    public function __construct($imagepath = NULL, $pattern = NULL)
    {
        // Prevent unnecessary warnings on servers that are set to display E_STRICT errors, these will damage the image data.
        error_reporting(error_reporting() & ~E_STRICT);
        
        // Set the config
        $this->load_config();
        
        // Try to create the cache directory if it does not exist
        $this->_create_cache_dir();

        $this->init_default_image();

        // Parse and set the image modify params
        $this->_set_params($imagepath, $pattern);

        if (empty($this->cached_file))
        {
            // Set the source file modified timestamp
            $this->source_modified = filemtime($this->source_file);

            // Try to create the mimic directory structure if required
            $this->_create_mimic_cache_dir();

            // Set the cached filepath with filename
            $this->cached_file = $this->cache_dir . pathinfo($this->source_file, PATHINFO_BASENAME);
        }
        // Create a modified cache file if required
        if ( ! $this->_cached_exists() AND $this->_cached_required())
        {
            $this->_create_cached();
        }
    }

    /**
     * Load main config and patterns
     */
    protected function load_config() {
        $this->config = Kohana::$config->load('imagecache');
        $this->config_patterns = Kohana::$config->load('imagecache_patterns');
    }

    /**
     * Initialize default image filepath
     */
    protected function init_default_image() {
        if( file_exists($this->config['default_image']))
        {
            $this->default_image = $this->config['default_image'];
        }
    }

    /**
     * Try to create the config cache dir if required
     * Set $cache_dir
     */
    protected function _create_cache_dir()
    {
        if( ! file_exists($this->config['cache_dir']))
        {
            try
            {
                mkdir($this->config['cache_dir'], 0755, TRUE);
            }
            catch(Exception $e)
            {
                throw new Kohana_Exception($e);
            }
        }
        
        // Set the cache dir
        $this->cache_dir = $this->config['cache_dir'];
    }
    
    /**
     * Try to create the mimic cache dir from the source path if required
     * Set $cache_dir
     */
    protected function _create_mimic_cache_dir()
    {
        // Get the dir from the source file
        $mimic_dir = $this->config['cache_dir'] . pathinfo($this->source_file, PATHINFO_DIRNAME);

        // Try to create if it does not exist
        if( ! file_exists($mimic_dir))
        {
            try
            {
                mkdir($mimic_dir, 0755, TRUE);
            }
            catch(Exception $e)
            {
                throw new Kohana_Exception($e);
            }
        }

        // Set the cache dir, with trailling slash
        $this->cache_dir = $mimic_dir.'/';
    }

    /**
     * Sets the operations params
     * 
     * @param string $imagepath Path of input image
     * @param string $pattern   Name of applied pattern
     * @throws HTTP_Exception_404
     */
    protected function _set_params($imagepath = NULL, $pattern = NULL)
    {
        // Get values from request if need
        if (empty($imagepath))
        {
            $imagepath = Request::current()->param('imagepath');
            if (!empty($this->default_image) && (empty($imagepath) || !file_exists($imagepath)))
            {
                $imagepath = $this->default_image;
            }
        }

        if (empty($pattern))
        {
            $pattern = Request::current()->param('pattern');
        }

        // If pattern not exist return 404
        if (!array_key_exists($pattern, $this->config_patterns)) {
            $this->_throw_404();
        }
        if (file_exists($imagepath))
        {
            $settings = $this->config_patterns->get($pattern);

            foreach ($settings as $key => &$value)
            {
                switch ($key)
                {
                    case 'quality':
                    case 'width':
                    case 'height':
                        $value = trim($value, 'px');
                        if (preg_match('/([0-9]*)%/', $value, $matches))
                        {
                            list($width, $height) = getimagesize($imagepath);
                            $value = $matches[1];
                            $value = ${$key} / 100 * $value;
                        }
                        $value = $key[0] . $value;
                        break;
                    case 'crop':
                        if (!empty($value))
                        {
                            $value = $key[0];
                        }
                        else
                        {
                            unset($value);
                        }
                        break;
                }
            }
            $params = implode('-', $settings);

            $this->image = Image::factory($imagepath);

            // The parameters are separated by hyphens
            $raw_params = explode('-', $params);

            // Update param values from passed values
            foreach ($raw_params as $raw_param)
            {
                $name = $raw_param[0];
                $value = substr($raw_param, 1, strlen($raw_param) - 1);

                if ($name == 'c')
                {
                    $this->url_params[$name] = TRUE;

                    // When croping, we must have a width and height to pass to imagecreatetruecolor method
                    // Make width the height or vice versa if either is not passed
                    if (empty($this->url_params['w']))
                    {
                        $this->url_params['w'] = $this->url_params['h'];
                    }
                    if (empty($this->url_params['h']))
                    {
                        $this->url_params['h'] = $this->url_params['w'];
                    }
                }
                elseif (key_exists($name, $this->url_params))
                {
                    // Remaining expected params (w, h, q)
                    $this->url_params[$name] = $value;
                }
                else
                {
                    // Watermarks or invalid params
                    $this->url_params[$raw_param] = $raw_param;
                }
            }

            //Do not scale up images
            if (!$this->config['scale_up'])
            {
                    if ($this->url_params['w'] > $this->image->width) $this->url_params['w'] = $this->image->width;
                    if ($this->url_params['h'] > $this->image->height) $this->url_params['h'] = $this->image->height;
            }

            // Must have at least a width or height
            if(empty($this->url_params['w']) AND empty($this->url_params['h']))
            {
                $this->_throw_404();
            }
        }
        else
        {
            // If original image not exist, then check for exist cache
            $cached = Request::current()->uri();
            if (file_exists($cached))
            {
                $this->cached_file = $cached;
            }
            else {
                // Original and cached files is not avaliable
                $this->_throw_404();
            }
        }

        // Set the url filepath
        $this->source_file = $imagepath;
    }

    protected function _throw_404() {
        throw new HTTP_Exception_404('The requested URL :uri was not found on this server.',
                                                array(':uri' => Request::$current->uri()));
    }

    /**
     * Checks if a physical version of the cached image exists
     * 
     * @return boolean
     */
    protected function _cached_exists()
    {
        return file_exists($this->cached_file);
    }
    
    /**
     * Checks that the param dimensions are are lower then current image dimensions
     * 
     * @return boolean
     */
    protected function _cached_required()
    {
        $image_info = getimagesize($this->source_file);
        
        if (($this->url_params['w'] == $image_info[0]) AND ($this->url_params['h'] == $image_info[1]))
        {
            $this->serve_default = TRUE;
            return FALSE;
        }
        
        return TRUE;
    }

    /**
     * Creates a cached cropped/resized version of the file
     */
    protected function _create_cached()
    {
        if($this->url_params['c'])
        {
            // Resize to highest width or height with overflow on the larger side
            $this->image->resize($this->url_params['w'], $this->url_params['h'], Image::INVERSE);
            
            // Crop any overflow from the larger side
            $this->image->crop($this->url_params['w'], $this->url_params['h']);
        }
        else
        {
            // Just Resize
            $this->image->resize($this->url_params['w'], $this->url_params['h']);
        }
        
        // Apply any valid watermark params
        $watermarks = Arr::get($this->config, 'watermarks');
        if ( ! empty($watermarks))
        {
            foreach ($watermarks as $key => $watermark)
            {
                if (key_exists($key, $this->url_params))
                {
                    $image = Image::factory($watermark['image']);
                    $this->image->watermark($image, $watermark['offset_x'], $watermark['offset_y'], $watermark['opacity']);
                }
            }
        }
        
        // Save
        if($this->url_params['q'])
		{
            //Save image with quality param
            $this->image->save($this->cached_file, $this->url_params['q']);
        }
        else
        {
            //Save image with default quality
            $this->image->save($this->cached_file, Arr::get($this->config, 'quality', 80));
        }
    }
    
    /**
     * Create the image HTTP headers
     * 
     * @param  string     path to the file to server (either default or cached version)
     */
    protected function _create_headers($file_data)
    {        
        // Create the required header vars
        $last_modified = gmdate('D, d M Y H:i:s', filemtime($file_data)).' GMT';
        $content_type = File::mime($file_data);
        $content_length = filesize($file_data);
        $expires = gmdate('D, d M Y H:i:s', (time() + $this->config['cache_expire'])).' GMT';
        $max_age = 'max-age='.$this->config['cache_expire'].', public';
        
        // Some required headers
        header("Last-Modified: $last_modified");
        header("Content-Type: $content_type");
        header("Content-Length: $content_length");

        // How long to hold in the browser cache
        header("Expires: $expires");

        /**
         * Public in the Cache-Control lets proxies know that it is okay to
         * cache this content. If this is being served over HTTPS, there may be
         * sensitive content and therefore should probably not be cached by
         * proxy servers.
         */
        header("Cache-Control: $max_age");
        
        // Set the 304 Not Modified if required
        $this->_modified_headers($last_modified);
        
        /**
         * The "Connection: close" header allows us to serve the file and let
         * the browser finish processing the script so we can do extra work
         * without making the user wait. This header must come last or the file
         * size will not properly work for images in the browser's cache
         */
        header("Connection: close");
    }
    
    /**
     * Rerurns 304 Not Modified HTTP headers if required and exits
     * 
     * @param  string  header formatted date
     */
    protected function _modified_headers($last_modified)
    {  
        $modified_since = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
            ? stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE'])
            : FALSE;

        if ( ! $modified_since OR $modified_since != $last_modified)
            return;

        // Nothing has changed since their last request - serve a 304 and exit
        header('HTTP/1.1 304 Not Modified');
        header('Connection: close');
        exit();
    }
    
    /**
     * Decide which filesource we are using and serve
     */
    protected function _serve_file()
    {
        // Set either the source or cache file as our datasource
        if ($this->serve_default)
        {
            $file_data = $this->source_file;
        }
        else
        {
            $file_data = $this->cached_file;
        }
        
        // Output the file
        return $file_data;
    }
    
    /**
     * Outputs the cached image file and exits
     */
    public function output_file()
    {
        $file_data = $this->_serve_file();
        // Create the headers
        $this->_create_headers($file_data);
        
        // Get the file data
        $data = file_get_contents($file_data);

        // Send the image to the browser in bite-sized chunks
        $chunk_size = 1024 * 8;
        $fp = fopen('php://memory', 'r+b');

        // Process file data
        fwrite($fp, $data);
        rewind($fp);
        while ( ! feof($fp))
        {
            echo fread($fp, $chunk_size);
            flush();
        }
        fclose($fp);

        exit();
    }
}

