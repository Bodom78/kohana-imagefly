<?php defined('SYSPATH') OR die('No direct script access.');

class Image_GD extends Kohana_Image_GD {

	protected function _do_frame($width, $height, $offset_x, $offset_y, $bg_color)
	{
		// Prepare the color
		if ($bg_color[0] === '#')
		{
			// Remove the pound
			$bg_color = substr($bg_color, 1);
		}
 
		if (strlen($bg_color) === 3)
		{
			// Convert shorthand into longhand hex notation
			$bg_color = preg_replace('/./', '$0$0', $bg_color);
		}

		// Convert the hex into RGB values
		list ($r, $g, $b) = array_map('hexdec', str_split($bg_color, 2));
		
		// Load the photo
		$this->_load_image($this->_image);
		
		// Create a canvas
		$canvas = $this->_create($width, $height);
		$color = imagecolorallocate($canvas, $r, $g, $b);
		imagefill($canvas, 0 , 0 , $color);
 
		// Stamp the image onto the canvas
		if (imagecopyresampled($canvas, $this->_image, $offset_x, $offset_y, 0, 0, $this->width, $this->height, $this->width, $this->height))
		{
			// Swap the new image for the old one
			imagedestroy($this->_image);
			$this->_image = $canvas;
 
			// Reset the width and height
			$this->width  = imagesx($canvas);
		}
	}
	
} // class Image_GD extends Kohana_Image_GD