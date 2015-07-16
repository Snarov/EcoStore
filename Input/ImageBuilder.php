<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Input;

require_once 'iObjectBuilder.php';
require_once 'Entities/Image.php';

use \Entities\Image;

/**
 * Строит объекты класса Image из сырых объектов. Сырые объекты, не соответствующие схеме
 *  "Картинка" из БД магазина игнорируются
 *
 * @author snarov
 * @package \Input
 */
class ImageBuilder implements iObjectBuilder {

	public function buildAll(array $objects) {
		global $imgPath;
		
		$retval = array();

		foreach ($objects as $object) {
			if (property_exists($object, 'virtuemart_media_id') && property_exists($object, 'file_title')){
				$image = new Image($object->virtuemart_media_id, $object->file_title);
				$retval[] = $image;
			}
		}
		
		return $retval;
	}

}
