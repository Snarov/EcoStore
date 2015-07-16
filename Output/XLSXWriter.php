<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Output;

require_once 'Output/Writer.php';
require_once 'PHPExcel.php';
require_once 'PHPExcel/Writer/Excel2007.php';

/**
 * Записывает выходные данные в файл таблиц формата XLSX
 *
 * @author snarov
 * @package \Output
 */
class XLSXWriter extends Writer{
	
	const WIDTH_MULTIPLIER = 8;
	
	/**
	 * @var Ключ: текст в ячейках заголовка таблицы. Значение: ширина столбца
	 */
	const header = array(
		'Номер товара' => 3.2,
		'Тип товара' => 2.6,
		'Категория' => 2.7,
		'Производитель' => 4.0,
		'Название' => 3.8,
		'Состав' => 2.1,
		'Краткое описание' => 4.1,
		'Полное описание' => 4.0,
		'Ключевые слова' => 3.8,
		'Цена' => 2.5,
		'Скидка' => 1.5
	);
	
	/**
	 * Для записи в файл таблиц использует библиотеку PHPExcell. Также копирует картинки в папку out/images/ с изменением имени.
	 * @param array $products
	 * @param string $imagesInDir
	 * @param string $imagesOutDir
	 */
	public function write(array $products, $imagesInDir, $imagesOutDir) {
		$excelDoc = new \PHPExcel();
		$excelDoc->setActiveSheetIndex();
		$activeSheet = $excelDoc->getActiveSheet();
		$activeSheet->setTitle("Таблица товаров");
		
		//Записываем заголовок
		$col = 'A';
		foreach(self::header as $headerElem => $elemWidth){
			$activeSheet->setCellValue($col . 1, $headerElem);
			$activeSheet->getColumnDimension($col++)->setWidth($elemWidth * WIDTH_MULTIPLIER);
		}
		$activeSheet->freezePane('A2');
		
		//записываем информацию о товарах 
		foreach($products as $index => $product){
			$activeSheet->setCellValue('A' . ($index + 2), $index + 1); //Номер товара
			$activeSheet->setCellValue('B' . ($index + 2), 1); // Тип товара
			$activeSheet->setCellValue('C' . ($index + 2), $product->category->name); // Категория
			$activeSheet->setCellValue('D' . ($index + 2), sprintf( // Производитель
					"%s;%s;%s",
					$product->manufacturer->name,
					$product->manufacturer->country,
					$product->manufacturer->url));
			$activeSheet->setCellValue('E' . ($index + 2), $product->name); // Название 
			$activeSheet->setCellValue('F' . ($index + 2), $product->ingredients); // Состав
			$activeSheet->setCellValue('G' . ($index + 2), $product->shortDescr); // Краткое описание
			$activeSheet->setCellValue('H' . ($index + 2), ''); // Полное описание
			$activeSheet->setCellValue('I' . ($index + 2), $product->keywords); // Ключевые слова
			$activeSheet->setCellValue('J' . ($index + 2), $product->price); // Цена
			$activeSheet->setCellValue('K' . ($index + 2), $product->sale); // Скидка
						
			$this->writeImages($product, $index + 1, $imagesInDir, $imagesOutDir);
		}
		
		//Сохраняем файл таблиц на диске
		$docWriter = new \PHPExcel_Writer_Excel2007($excelDoc);
		$docWriter->save($this->fileName);
		
		
	}

}

?>
