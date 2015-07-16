<?php

namespace Input;

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

/**
 * Этот абстрактный класс является общим для всех классов-читальщиков различных форматов
 * 
 * @author snarov
 * @package \Input
 */
abstract class Reader {

	/**
	 *
	 * @var string Хранит в себе данные, в том виде, в котором они находились в файле
	 */
	protected $fileText;

	/**
	 *
	 * @var bool Если true то выводит информацию о прогрессе
	 */
	protected $verbose = true;

	/**
	 * Открывает файл, с указанным именем и считывает его в память.
	 * 
	 * @param string $fileName
	 */
	function read($fileName) {
		$this->fileText = file_get_contents($fileName);
		$this->fileText = strip_tags($this->fileText);
	}

	/**
	 * Формирует и возвращает массив объектов, информация о которых содержалась в считанном файле
	 */
	abstract function getObjects();
}
