<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Input;

require_once 'iObjectBuilder.php';
require_once 'Entities/Category.php';

use \Entities\Category;

/**
 * Строит объекты класса Category из сырых объектов. Сырые объекты, не соответствующие схеме
 *  "Категория"из БД магазина игнорируются
 *
 * @author snarov
 * @package \Input
 */
class CategoryBuilder implements iObjectBuilder {

	public function buildAll(array $objects) {
		$retval = array();

		foreach ($objects as $object) {
			if (property_exists($object, 'virtuemart_category_id') && property_exists($object, 'category_name')){
				$category = new Category($object->virtuemart_category_id, $object->category_name);
				$retval[] = $category;
			}
		}
		
		return $retval;
	}

}
