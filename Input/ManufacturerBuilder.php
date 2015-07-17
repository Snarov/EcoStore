<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Input;

require_once 'iObjectBuilder.php';
require_once 'Entities/Manufacturer.php';

use \Entities\Manufacturer;

/**
 * Строит объекты класса Manufacturer из сырых объектов. Сырые объекты, не соответствующие схеме
 *  "Производитель"из БД магазина игнорируются
 *
 * @author snarov
 * @package \Input
 */
class ManufacturerBuilder implements iObjectBuilder {

	public function buildAll(array $objects) {
		$retval = array();

		foreach ($objects as $object) {
			if (property_exists($object, 'virtuemart_manufacturer_id') && property_exists($object, 'mf_name') &&
					property_exists($object, 'mf_url')) {

				global $translit;

				if (preg_match('/\([^\W\d]+\)/', $object->slug, $matches) === 1) {
					$country = $matches[0];
				} else {
					$country = '';
				}

				$country = strtr($country, array_flip($translit));
				$retval[] = new Manufacturer($object->virtuemart_manufacturer_id, $object->mf_name, $country, $object->mf_url);
			}
		}
		return $retval;
	}

}
