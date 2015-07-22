<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Output;

require_once 'Output/ProductsWriter.php';
require_once 'PHPExcel.php';
require_once 'PHPExcel/Writer/Excel2007.php';
require_once 'PHPExcel/Style/Alignment.php';
require_once 'PHPExcel/Style/Fill.php';

/**
 * Записывает выходные данные в файл таблиц формата XLSX
 *
 * @author snarov
 * @package \Output
 */
class XLSXWriter extends ProductsWriter {

	const WIDTH_MULTIPLIER = 8;

	/**
	 * @var определяет стиль заголовка таблицы
	 */
	const HEADER_STYLE = array(
		'font' => array(
			'bold' => true,
			'name' => 'Calibri',
			'size' => 11,
			'color' => array(
				'rgb' => 'FFFFFF'
			)
		),
		'alignment' => array(
			'horizontal' => \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
			'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
		),
//Заполнение цветом
		'fill' => array(
			'type' => \PHPExcel_STYLE_FILL::FILL_SOLID,
			'color' => array(
				'rgb' => 'A9D18E'
			)
		)
	);
	
	/**
	 * @var Максимальная длина строки в полях типа "Описание"
	 */
	const MAX_LEN = 8;
	
	/**
	 * @var Ключ: текст в ячейках заголовка таблицы. Значение: ширина столбца
	 */
	const header = array(
		'Номер товара' => 3.0,
		'Тип товара' => 2.6,
		'Категория' => 2.7,
		'Производитель' => 4.0,
		'Название' => 3.8,
		'Состав' => 2.1,
		'Краткое описание' => 4.1,
		'Ключевые слова' => 3.8,
		'Цена' => 2.0,
		'Вес' => 2.0
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
		foreach (self::header as $headerElem => $elemWidth) {
			$activeSheet->setCellValue($col . 1, $headerElem);
			$activeSheet->getColumnDimension($col++)->setWidth($elemWidth * self::WIDTH_MULTIPLIER);
		}
		--$col;
		$activeSheet->freezePane('A2');		// Закрепляем шапку
		$activeSheet->getStyle("A1:{$col}1")->applyFromArray(self::HEADER_STYLE); // и применяем к ней стиль 

//записываем информацию о товарах 
		$activeSheet->getStyle('A1:K' . (count($products) + 1))->getAlignment()->setWrapText(true);
		
		mkdir($imagesOutDir);
		foreach ($products as $index => $product) {
			$activeSheet->setCellValue('A' . ($index + 2), $index + 1); //Номер товара
			$activeSheet->setCellValue('B' . ($index + 2), 1); // Тип товара
			$activeSheet->setCellValue('C' . ($index + 2), $product->category->name); // Категория
			$activeSheet->setCellValue('D' . ($index + 2), sprintf(// Производитель
							"%s;%s;%s", $product->manufacturer->name, $product->manufacturer->country, $product->manufacturer->url));
			$activeSheet->setCellValue('E' . ($index + 2), $product->name); // Название 
			$activeSheet->setCellValue('F' . ($index + 2), $product->ingredients); // Состав
			$activeSheet->setCellValue('G' . ($index + 2), $product->shortDescr); // Краткое описание
			$activeSheet->setCellValue('H' . ($index + 2), $product->keywords); // Ключевые слова
			$activeSheet->setCellValue('I' . ($index + 2), $product->price); // Цена
			$activeSheet->setCellValue('J' . ($index + 2), $product->weight); // вес

			$this->writeImages($product, $index + 1, $imagesInDir, $imagesOutDir);
			
			$activeSheet->getRowDimension($index + 2)->setRowHeight(-1);
			
		}

//Сохраняем файл таблиц на диске
		$docWriter = new \PHPExcel_Writer_Excel2007($excelDoc);
		$docWriter->save($this->fileName);
	}

}

?>
