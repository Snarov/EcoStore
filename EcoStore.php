#!/usr/local/bin/php

<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

set_include_path(get_include_path() . PATH_SEPARATOR . '/usr/local/lib/php/PHPExcel-1.8.0/Classes/' . 
				PATH_SEPARATOR . __DIR__);	//подключаем библиотеку PHPExcell

error_reporting(0); 

//MESSAGES
define('ERROR', 'Ошибка: '); // используется всеми уведомлениями об ошибках
define('NOTICE', 'Уведомление :');
define('NO_READER', 'Reader отсутствует');
define('NO_WRITER', 'Writer отсутствует');
define('NO_MANUFACTURER', 'У товара не задан поставщик');
define('NO_CATEGORY', 'У товара не задана категория');
define('IMG_COPY_FAIL', 'Не удается найти файл');

require_once 'ScriptParams.php';
require_once 'Input/ReaderFactory.php';
require_once 'Entities/Manufacturer.php';
require_once 'Input/ManufacturerBuilder.php';
require_once 'Input/CategoryBuilder.php';
require_once 'Input/ImageBuilder.php';
require_once 'Input/ProductBuilder.php';
require_once 'Output/WriterFactory.php';

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

//Записываем все данный в файл
if(!($writer = WriterFactory::getWriter($scriptParams->outType, $scriptParams->outFileName))){ //получаем Writer, соответствующий формату
	exit(ERROR . "{$scriptParams->outType}: " . NO_WRITER);
}

$writer->write($products, $scriptParams->imagesDir, $outImagesDir);

echo "Готово";

?>