<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Input;

require_once 'Reader.php';

/**
 * Класс, обеспечивающий непосредственное чтение данных прямо из БД. Является общим для классов,
 * обеспечивающих работу с различными СУБД
 *
 * @author snarov
 * @package \Input
 */
abstract class DBReader extends Reader{
		
	/**
	 * @var string 
	 */
	protected $server;
	
	/**
	 * @var string
	 */
	protected $username;
	
	/**
	 * @var string
	 */
	protected $userPassword;
	
	/**
	 * @var string
	 */
	protected $DBName;
	
	/**
	 * @var Traversable Представление таблицы из БД
	 */
	protected $table;
	
	function __construct($server, $username, $userPassword, $DBName){
		$this->server = $server;
		$this->username = $username;
		$this->userPassword = $userPassword;
		$this->DBName = $DBName;
		
	} 
	
	/**
	 * Функция производит чтение таблицы из бд и сохраняет ее в поле table
	 * @see DBReader::$table
	 * @param string $tableName
	 */
	abstract function pull($tableName);
}
