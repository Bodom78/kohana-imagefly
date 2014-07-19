<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package   Modules
 * @category  Imagecache
 * @author    Vyacheslav Malchik <validoll-ru@yandex.ru>
 * @uses      Imagefly Module
 */

class Controller_Imagecache extends Controller {
    
    public function action_index()
    {
        $this->auto_render = FALSE;
        $image = new ImageCache();
        $image->output_file();
    }
}
