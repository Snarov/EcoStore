<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Entities;

require_once 'Entity.php';

/**
 * Класс, представляющий поставщика товаров в магазине
 *
 * @author snarov
 * @package Entities
 * 
 * @property string $name
 * @property string $country
 * @property string $url Ссылка на страницу поставщика
 */
class Manufacturer extends Entity{
	private $name;
	private $country;
	private $url;
	
	function __get($name) {
		return $this->$name;
	}

	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __construct($id, $name = '', $country = '', $url = '') {
		parent::__construct($id);
		$this->name = $name;
		$this->country = $country;
		$this->url = $url;
	}
}
