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
class MySQLReader extends DBReader {

	/**
	 * @var string Запрос для выбора всех записей из таблицы с заданным именем
	 */
	const QUERY_TEMPLATE = "SELECT * FROM %s";

	public function getObjects() {
		$objects = array();
		
		while ($row = $this->table->fetch_assoc()){
			$objects[] = (object)$row;
		}
		
		return $objects;
	}

	/**
	 * Вытягивает все записи из таблицы с именем $tableName. Использует mysqli
	 * @link http://php.net/manual/ru/book.mysqli.php
	 * @param type $tableName
	 * @retval bool true в случае удачи, false в случае неудачи
	 */
	public function pull($tableName) {
		$mysqli = new \mysqli($this->server, $this->username, $this->userPassword, $this->DBName);
		$mysqli->set_charset("utf8");		
		if ($mysqli->connect_errno) {
			echo ERROR . "Не удалось подключиться к MySQL: " . $mysqli->connect_error . "\n";
			return false;
		} 
		
		if($this->table = $mysqli->query(sprintf(self::QUERY_TEMPLATE, $tableName))){
			return true;
		}else{
			echo ERROR . 'не удалось получить данные таблицы ' . $tableName . "\n";
			return false;
		}
		
	}

}
