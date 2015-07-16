<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Input;

/**
 * Интерфейс для строителей объектов сущностей
 * @author snarov
 * @package \Input
 */
interface iObjectBuilder {

	/**
	 * Cтроит массив объектов сущностей из массива сырых объектов
	 * @param object[] $objects массивы сырых объектов
	 * @return Entities[] массив су
	 */
	function buildAll(array $objects);
}
