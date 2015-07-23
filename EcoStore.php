#!/usr/local/bin/php

<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

//error_reporting(0); 

//обработка изображений высокого разрешения требует больше памяти
ini_set('memory_limit', '512M');

//подключаем необходиые библиотеки
set_include_path(get_include_path() . PATH_SEPARATOR .
				'libs/PHPExcel-1.8.0/Classes/' . PATH_SEPARATOR .
				'libs/phpword' . PATH_SEPARATOR .
				'libs/simplehtmldom' . PATH_SEPARATOR . 
				'libs/htmltodocx_converter' . PATH_SEPARATOR .
				PATH_SEPARATOR . __DIR__);	


//MESSAGES
define('ERROR', 'Ошибка: '); // используется всеми уведомлениями об ошибках
define('NOTICE', 'Уведомление:');
define('NO_READER', 'Reader отсутствует');
define('NO_WRITER', 'Writer отсутствует');
define('NO_MANUFACTURER', 'У товара не задан производитель');
define('NO_CATEGORY', 'У товара не задана категория');
define('IMG_COPY_FAIL', 'Не удается скопировать файл');

require_once 'ScriptParams.php';
require_once 'Input/ReaderFactory.php';
require_once 'Entities/Manufacturer.php';
require_once 'Input/ManufacturerBuilder.php';
require_once 'Input/CategoryBuilder.php';
require_once 'Input/ImageBuilder.php';
require_once 'Input/ProductBuilder.php';
require_once 'Output/WriterFactory.php';
require_once 'Output/DOCXDescWriter.php';

use Input\ReaderFactory;
use Input\ManufacturerBuilder;
use Input\CategoryBuilder;
use Input\ImageBuilder;
use Input\ProductBuilder;
use Output\WriterFactory;


/**
 * @global ScriptParams $scriptParams 
 */
$scriptParams = new ScriptParams();

/**
 * @global string $imgPath директория с картинками (выходная)
 */
$outImagesDir = 'images';

//Считываем все данные из всех файлов
if(!($reader = ReaderFactory::getReader($scriptParams->inType))){ //получаем Reader, соответствующий формату
	exit(ERROR . "{$scriptParams->inType}: " . NO_READER);
}


$reader->read($scriptParams->productsIFileName);
$productRawObjects = $reader->getObjects();

$reader->read($scriptParams->manufacturersIFileName);
$manufacturerRawObjects = $reader->getObjects();

$reader->read($scriptParams->categoriesIFileName);
$categoryRawObjects = $reader->getObjects();

$reader->read($scriptParams->mediasIFileName);
$imageRawObjects = $reader->getObjects();

$reader->read($scriptParams->productsCategoriesBindIFileName);
$productCategoryBinds = $reader->getObjects();

$reader->read($scriptParams->productsManufacturersBindIFileName);
$productManufacturerBinds = $reader->getObjects();

$reader->read($scriptParams->productsMediasBindIFileName);
$productImageBinds = $reader->getObjects();

$reader->read($scriptParams->productPricesIFileName);
$productPrices = $reader->getObjects();

//формируем объекты из прочитанных данных
$manufacturerBuilder = new ManufacturerBuilder;
$manufacturers = $manufacturerBuilder->buildAll($manufacturerRawObjects);

$categoryBuilder = new CategoryBuilder;
$categories = $categoryBuilder->buildAll($categoryRawObjects);

$imageBuilder = new ImageBuilder;
$images = $imageBuilder->buildAll($imageRawObjects);

$productBuilder = new ProductBuilder;
$products = $productBuilder->buildAll(
		$manufacturers,
		$categories,
		$images,
		$productManufacturerBinds,
		$productCategoryBinds,
		$productImageBinds,
		$productPrices,
		$productRawObjects);


if(!($writer = WriterFactory::getWriter($scriptParams->outType, $scriptParams->outFileName))){ //получаем Writer, соответствующий формату
	exit(ERROR . "{$scriptParams->outType}: " . NO_WRITER);
}

$writer->write($products, $scriptParams->imagesDir, $outImagesDir);

$writer = new Output\DOCXDescWriter($scriptParams->descFileName);
$writer->writeDesc($products);

?>