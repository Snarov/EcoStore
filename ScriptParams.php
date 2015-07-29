<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

/**
 * Класс, отвечающий за хранение и предоставление информации о параметрах выполнения сценария. Состояние экземпляра класса
 * определяется параметрами командной строки.
 *
 * @author snarov
 * @version 1.0
 * 
 * @property-read string $productsIFileName
 * @property-read string $manufacturersIFileName
 * @property-read string $mediasIFileName
 * @property-read string $categoriesIFileName
 * @property-read string $productsCategoriesBindIFileName
 * @property-read string $productsManufacturersBindIFileName
 * @property-read string $productsMediasBindIFileName
 * @property-read string $productPricesIFileName
 * @property-read bool $db Указывает на то, включен ли режим чтения из БД
 * @property-read string $DBMSName
 * @property-read string $hostname
 * @property-read string $username
 * @property-read string $password
 * @property-read string $DBName
 * @property-read string $inType
 * @property-read string $outType
 * @property-read string $outFileName
 * @property-read string $descFileName
 * @property-read string $imagesDir
 * @property-read bool $dataCorrectionOn Режим корректирования входных данных может потребоваться в будущем
 */
class ScriptParams {

	/**
	 * @var array
	 */
	const OPTIONS = array(
		'i:' => 'input:',
		'd:' => 'database:',
		'o:' => 'output:',
		'w:' => 'woutput:',
		's:' => 'itype:',
		'f:' => 'otype:',
		'c::' => 'correction::'
	);

	/**
	 * @var help
	 */
	const HELP = <<<'EOT'
Использование: EcoStore.php -i "ИмяФайлаСТоварами ИмяФайлаСПроизводителями ИмяФайлаСКартинками
				ИмяФайлаСКатегориями ИмяФайлаСвязейСКатегориями, ИмяФайлаСвязейСПроизводителями
				ИмяФайлаСвязейСКартинками ИмяФайлаЦен ДиректорияСКартинками" || -d "DBMSName hostname username password dbName"
				
			[-o ИмяВыходногоФайла -w ИмяФайлаОписаний -s ФорматВходныхФайлов -f ФорматВыходногоФайла -с]

Опции:
-i --input	: Задает имена входных файлов
-d --database	: Указывает скрипту производить считывание входных данных прямиком из БД
-o --output	: Задает имя выходного файла
-w --woutput	: Задает имя выходного файла, содержащего полное описание товаров 
-s --itype	: Задает формат входных файлов
-f --otype	: Задает формат выходного файла
-c --correction	: Включает коррекцию считываемых данных (пока не реализовано)
EOT;

	/**
	 * @var int
	 */
	const FILES_COUNT = 9;
	
	/**
	 * @var type int
	 */
	const DBMS_CONNECTION_WORDS_COUNT = 5;
	
	private $productsIFileName;
	private $manufacturersIFileName;
	private $mediasIFileName;
	private $categoriesIFileName;
	private $productsCategoriesBindIFileName;
	private $productsManufacturersBindIFileName;
	private $productsMediasBindIFileName;
	private $productPricesIFileName;
	private $db = false;
	private $DBMSName;
	private $hostname;
	private $username;
	private $password;
	private $DBName;
	private $inType = "JSON";
	private $outType = "xlsx";
	private $outFileName = "EcoStoreTable.xlsx";
	private $descFileName = "EcoStoreDescriptions.docx";
	private $imagesDir = 'data/images';
	private $dataCorrectionOn = false;

	function __construct() {
		//здесь происходит разбор опций командной строки и установление состояния объекта

		$params = getopt(implode('', array_keys(self::OPTIONS)), self::OPTIONS);

		if (!empty($params['s'])) {
			$this->inType = $params['s'];
		} else if (!empty($params['itype'])) {
			$this->inType = $params['itype'];
		}

		if (!empty($params['i'])) {
			$fileNames = trim($params['i']);
		} else if (!empty($params['input'])) {
			$fileNames = trim($params['input']);
		} else if (!empty($params['d'])) {
			$dbConnection = trim($params['d']);
		} else if (!empty($params['database'])) {
			$dbConnection = trim($params['database']);
		} else{
			$this->handleBadParams();
		}

		if ($dbConnection) {
			$this->db = true;
			$dbConnection = preg_split('/[\s]+/', $dbConnection);
			
			if (count($dbConnection) >= self::DBMS_CONNECTION_WORDS_COUNT){
				$this->DBMSName = $dbConnection[0];
				$this->hostname = $dbConnection[1];
				$this->username = $dbConnection[2];
				$this->password = $dbConnection[3];
				$this->DBName = $dbConnection[4];
			}else{
				$this->handleBadParams();
			}
		}else if ($fileNames) {
			$fileNames = preg_split('/[\s]+/', $fileNames);

			if (count($fileNames) >= self::FILES_COUNT) {
				$this->productsIFileName = $fileNames[0];
				$this->manufacturersIFileName = $fileNames[1];
				$this->mediasIFileName = $fileNames[2];
				$this->categoriesIFileName = $fileNames[3];
				$this->productsCategoriesBindIFileName = $fileNames[4];
				$this->productsManufacturersBindIFileName = $fileNames[5];
				$this->productsMediasBindIFileName = $fileNames[6];
				$this->productPricesIFileName = $fileNames[7];
				$this->imagesDir = $fileNames[8];
			} else {
				$this->handleBadParams();
			}
		}
		if (!empty($params['o'])) {
			$this->outFileName = $params['o'];
		} else if (!empty($params['output'])) {
			$this->outFileName = $params['output'];
		}

		if (!empty($params['w'])) {
			$this->descFileName = $params['w'];
		} else if (!empty($params['woutput'])) {
			$this->descFileName = $params['woutput'];
		}

		if (!empty($params['f'])) {
			$this->outType = $params['f'];
		} else if (!empty($params['otype'])) {
			$this->outType = $params['otype'];
		}

		if (isset($params['c']) || isset($params['correction'])) {
			$this->dataCorrectionOn = true;
		}
	}

	//Все поля только для чтения
	function __get($name) {
		return $this->$name;
	}

	/**
	 * Функция вызывается в случае поступления некорректной строки параметров. Она выводит help и завершает работу скрипта
	 */
	private function handleBadParams() {
		exit(self::HELP . "\n");
	}

}
