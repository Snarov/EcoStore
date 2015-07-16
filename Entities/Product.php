<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Entities;

require_once 'Entity.php';

/**
 * Класс, представляющий товар в магазине
 *
 * @author snarov
 * @package \Entities
 * 
 * @property string $name
 * @property Category $category 
 * @property Manufacturer $manufacturer
 * @property string $ingredients
 * @property string $shortDescr
 * @property string $fullDescr
 * @property string $keywords
 * @property int $price
 * @property float $sale
 * @property-read Image[] $images;
 * @property-read ProductSpecs $otherSpecs возможно понадобятся в будущем
 */
class Product extends Entity {

	private $name;
	private $category;
	private $manufacturer;
	private $ingredients;
	private $shortDescr;
	private $fullDescr;
	private $keywords;
	private $price;
	private $sale;
	private $images = array();
	private $otherSpecs;

	function __set($name, $value) {
		if (strcmp($name, 'images') === 0 || strcmp($name, 'otherSpecs') === 0) {
			\triggerError('Cannot access private property ' . __NAMESPACE__ . '\\' .
					__CLASS__ . '::' . $name . ' (from magic setter)');
		} else {
			$this->$name = $value;
		}
	}
	
	function __get($name) {
		return $this->$name;
	}

	function addImage(Image $img) {
		if (!in_array($img, $this->images)) {
			$this->images[] = $img;
		}
	}

	/**
	 * @todo Если понадобится, то добавить функцию addOtherSpec()
	 */
}
