<?php
/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Input;

/**
 * Фабрика объектов-читальщиков из файлов
 *
 * @author snarov
 * @package \Input
 */
class ReaderFactory {
	/**
	 * @var string[] отображение, связывающее типы входных файлов и Reader'ы для их чтения
	 */
	const MAP = array(
		"json" => "JSONReader"
		);
	
	/**
	 * Создает объект Reader'а взависимости от типа формата ввода
	 * @param string $iType 
	 * @return Reader объект для чтения файла нужного формата. null если нету Reader'а для такого формата
	 */
	static function getReader($iType){
		$iType = strtolower($iType);
		
		if(array_key_exists($iType, self::MAP)){
			eval('$readerClass = self::MAP[$iType];');	//eval для подавления ложной ошибки NetBeans
			require $readerClass . '.php';
			$readerClass = "\\Input\\$readerClass";
			$retval = new $readerClass;
		}else{
			$retval = null;
		}
		
		return $retval;
	}
}
