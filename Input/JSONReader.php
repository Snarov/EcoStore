<?php


/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Input;

require_once 'FileReader.php';
/**
 * Считывает из файла данные в формате JSON  и формирует из них сырые объекты.
 *
 * @author snarov
 * @package \Input
 */
class JSONReader extends FileReader {

	/**
	 * Возвращает массив объектов, полученных из JSON файла
	 * @return object[] Массив объектов, полученных из JSON файла. Пустой массив если не прочитано ничего
	 */
	function getObjects() {
		$objects = array();

		//позиции открывающей и закрывающей фигурных скобок (Ограничивают один JSON-объект)
		$open = $close = 0;
		while (true) {
			$open = strpos($this->fileText, '{', $close);
			$close = strpos($this->fileText, '}', $open + 1);

			if ($open !== false && $close !== false) {
				$JSONString = substr($this->fileText, $open, $close - $open + 1);
				$object = json_decode($JSONString);
				if(!empty($object)){
					$objects[] =  $object;
				}else{
					echo json_last_error_msg() . "\n";
					file_put_contents("out", $JSONString);
			}
			} else {
				break;
			}
		}

		return $objects;
	}
	
	function read($fileName) {
		parent::read($fileName);
		
		//избавляемся от переносов строк, табуляций и от слешей, экранирующих одинарные кавычки.
		$this->fileText = str_replace(array("\t", "\'", "\n", "\r"), array(' ', "'", ' ', ' '), $this->fileText);			
	}

}
