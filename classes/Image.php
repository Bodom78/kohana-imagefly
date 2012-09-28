<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Image extends Kohana_Image {

	/**
	 * Frame an image to the given size. Both width and height are required. This
	 * method will perform a crop in case width or height are smaller than the
	 * images initial value.
	 *
	 *
	 * If no offset is specified, the center of the axis will be used.
	 *
	 *     // Frame the image to 200x200 pixels, from the center
	 *     $image->frame(200, 200);
	 *
	 * @param   integer  new width
	 * @param   integer  new height
	 * @param   mixed    offset from the left
	 * @param   mixed    offset from the top
	 * @return  $this
	 * @uses    Image::_do_frame
	 */
	public function frame($width, $height, $offset_x = NULL, $offset_y = NULL, $bg_color = '#FFFFFF')
	{

		// First, crop
		if ($this->width < $width || $this->height < $height)
		{
			$this->crop($width, $height, $offset_x, $offset_y);
		}

		if ($offset_x === NULL)
		{
			// Center the X offset
			$offset_x = round(($width - $this->width) / 2);
		}
		if ($offset_y === NULL)
		{
			// Center the Y offset
			$offset_y = round(($height - $this->height) / 2);
		}

		$this->_do_frame($width, $height, $offset_x, $offset_y, $bg_color);

		return $this;

	} // public function frame


}