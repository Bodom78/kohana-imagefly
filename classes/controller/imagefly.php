<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @package   Modules
 * @category  Imagefly
 * @author    Fady Khalife
 * @uses      Image Module
 * 
 * Concept based on the smart-lencioni-image-resizer by Joe Lencioni
 * http://code.google.com/p/smart-lencioni-image-resizer/
 */
 
class Controller_ImageFly extends Controller
{
    public function action_index()
    {
        $this->auto_render = FALSE;
        new ImageFly();
    }
}
