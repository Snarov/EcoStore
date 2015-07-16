<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */


namespace Entities;

require_once 'Entity.php';

/**
 * Класс, представляющий картинку товара в магазине
 *
 * @author snarov
 * @package \Entities
 * 
 * @property type $path 
 */
class Image extends Entity {

	private $path;
	
	function __get($name) {
		return $this->$name;
	}

	function __set($name, $value) {
		$this->$name = $value;
	}

	function __construct($id, $path = '') {
		parent::__construct($id);
		
		// Корректируем кривое расширение файла картинки 
		$pathParts = explode('.', $path);
		$pathParts[count($pathParts) - 1] = substr($pathParts[count($pathParts) - 1], 0, 3);
		$path = implode('.', $pathParts);
		
		$this->path = $path;
	}

}
