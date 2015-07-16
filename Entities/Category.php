<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Entities;

require_once 'Entity.php';

/**
 * Класс, представляющий категорию товаров в магазине
 *
 * @author snarov
 * @package \Entities
 * 
 * @property string $name
 */
class Category extends Entity {

	private $name;
	
	function __get($name) {
		return $this->$name;
	}

	function __set($name, $value) {
		$this->$name = $value;
	}

	function __construct($id, $name = '') {
		parent::__construct($id);
		$this->name = $name;
	}

}
