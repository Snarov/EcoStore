<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Input;

/**
 * Фабрика объектов-читальщиков из БД
 *
 * @author snarov
 * @package \Input
 */
class DBReaderFactory {

	/**
	 * @var string[] отображение, связывающее СУБД и Reader'ы для чтения из них
	 */
	const MAP = array(
		"mysql" => "MySQLReader"
	);

	/**
	 * Создает объект Reader'а взависимости от типа формата ввода
	 * @param string $iType 
	 * @return Reader объект для чтения файла нужного формата. null если нету Reader'а для такого формата
	 */
	static function getReader($DBMSName, $host, $username, $password, $DBName) {
		$DBMSName = strtolower($DBMSName);

		if (array_key_exists($DBMSName, self::MAP)) {
			eval('$readerClass = self::MAP[$DBMSName];'); //eval для подавления ложной ошибки NetBeans
			require $readerClass . '.php';
			$readerClass = "\\Input\\$readerClass";
			$retval = new $readerClass($host, $username, $password, $DBName);
		} else {
			$retval = null;
		}

		return $retval;
	}

}
