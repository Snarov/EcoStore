<?php

/**
 * Класс, позволяющий работать с изображениями, манипулирую их размерами
 * 
 * @author Simon Jarvis
 * */
class SimpleImage {

	var $image;
	var $image_type;

	function load($filename) {
		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
		if ($this->image_type == IMAGETYPE_JPEG) {
			$this->image = imagecreatefromjpeg($filename);
		} elseif ($this->image_type == IMAGETYPE_GIF) {
			$this->image = imagecreatefromgif($filename);
		} elseif ($this->image_type == IMAGETYPE_PNG) {
			$this->image = imagecreatefrompng($filename);
		}
	}

	function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = null) {
		if ($image_type == IMAGETYPE_JPEG) {
			imagejpeg($this->image, $filename, $compression);
		} elseif ($image_type == IMAGETYPE_GIF) {
			imagegif($this->image, $filename);
		} elseif ($image_type == IMAGETYPE_PNG) {
			imagepng($this->image, $filename);
		}
		if ($permissions != null) {
			chmod($filename, $permissions);
		}
	}

	function output($image_type = IMAGETYPE_JPEG) {
		if ($image_type == IMAGETYPE_JPEG) {
			imagejpeg($this->image);
		} elseif ($image_type == IMAGETYPE_GIF) {
			imagegif($this->image);
		} elseif ($image_type == IMAGETYPE_PNG) {
			imagepng($this->image);
		}
	}

	function getWidth() {
		return imagesx($this->image);
	}

	function getHeight() {
		return imagesy($this->image);
	}

	function resizeToHeight($height) {
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width, $height);
	}

	function resizeToWidth($width) {
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->resize($width, $height);
	}

	function scale($scale) {
		$width = $this->getWidth() * $scale / 100;
		$height = $this->getheight() * $scale / 100;
		$this->resize($width, $height);
	}

	function resize($width, $height) {
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
	}

	/**
	 * Делает изображение квадратным, не обрезая его, а дополняя пустое пространство. За длину стороны квадрата принимается длина
	 * наибольшей стороны картинки 
	 *  
	 * @author snarov
	 * 
	 */
	function toSquare() {
		if ($this->getWidth() != $this->getHeight()) {
			$size = max(array($this->getWidth(), $this->getHeight()));
			
			
			$new_image = imagecreatetruecolor($size, $size);
			
			//добавляем белые (прозрачные) рамки
			$color = imagecolorallocate($new_image, 255, 255, 255);
			
			$retval2 = imagefill($new_image, 0, 0, $color);
//			$retval1 = imagecolortransparent($new_image, $color);
			
			$dstX = ($size - $this->getWidth()) / 2;
			$dstY = ($size - $this->getHeight()) / 2;

			imagecopyresampled($new_image, $this->image, $dstX, $dstY, 0, 0, $this->getWidth(), $this->getHeight());
				

			$this->image = $new_image;
		}
	}

	function __destruct() {
		if ($this->image) {
			imagedestroy($this->image);
		}
	}

}

?>
