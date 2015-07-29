<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Input;

require_once 'DBReader.php';

/**
 * Считывает таблицы из БД, находящейся под управлением MySQL
 *
 * @author snarov
 * @package \Input
 */
class MySQLReader extends DBReader{
	
	/**
	 * @var string Запрос для выбора всех записей из таблицы с заданным именем
	 */
	const QUERY_TEMPLATE = "SELECT * FROM %s";
	
	public function getObjects() {
		
	}

	/**
	 * Использует mysqli
	 * @link http://php.net/manual/ru/book.mysqli.php
	 * @param type $tableName
	 */
	public function pull($tableName) {
		
	}
}
