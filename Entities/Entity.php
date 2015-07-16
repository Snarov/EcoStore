<?php

namespace Entities;

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

/**
 * Абстрактый класс, обобщающий классы предметной области (напр. Продукт или Производитель)
 * @package \Entities
 * @author snarov
 * 
 * @property-read int $id У каждого экземпляра свой id
 */
abstract class Entity {

	protected $id;
	
	public function __construct($id){
		$this->id = $id;
	}
}
