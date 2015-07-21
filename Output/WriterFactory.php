<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Output;

/**
 * Фабрика объекто, проивзодящих запись в файл
 *
 * @author snarov
 * @package \Output
 */
class WriterFactory {
	/**
	 * @var string[] отображение, связывающее типы выходных файлов и Writer'ы для их чтения
	 */
	const MAP = array(
		"xlsx" => "XLSXWriter",
		"docx" => "DOCXWriter"
	);
	
	/**
	 * Создает объект Writer'а взависимости от типа формата вывода
	 * @param string $oType 
	 * @param string $fileName
	 * @return Writer объект для записи файла нужного формата. null если нету Writer'а для такого формата
	 */
	static function getWriter($oType, $fileName){
		$oType = strtolower($oType);
		
		if(array_key_exists($oType, self::MAP)){
			eval('$writerClass = self::MAP[$oType];');	//eval для подавления ложной ошибки NetBeans
			require $writerClass . '.php';
			$writerClass = "\\Output\\$writerClass";
			$retval = new $writerClass($fileName);
		}else{
			$retval = null;
		}
		
		return $retval;
	}
}
