<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Output;

/**
 * Класс, наследуемый классами, которые производят запись выходных данных в файл
 * @author snarov
 * @package \Output
 */
abstract class Writer {

	protected $fileName;

	function __construct($fileName) {
		$this->fileName = $fileName;
	}

}
