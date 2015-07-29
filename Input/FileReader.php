<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Input;

require_once 'Reader.php';

/**
 *
 * Этот абстрактный класс является общим для всех классов-читальщиков из файлов различных форматов
 * 
 * @author snarov
 * @package \Input
 */
abstract class FileReader extends Reader{
		/**
	 *
	 * @var string Хранит в себе данные в том виде, в котором они находились в файле
	 */
	protected $fileText;
	
	/**
	 * Открывает файл, с указанным именем и считывает его в память.
	 * 
	 * @param string $fileName
	 */
	function read($fileName) {
		$this->fileText = file_get_contents($fileName);
	}
}
