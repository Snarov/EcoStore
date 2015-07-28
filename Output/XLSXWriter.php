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
	 * @var связывает названия категорий naturlife с  типами товаров в ecostore. 
	 */
	const TYPE_MAP = array(
	"Для бани, ванны и душа" => 1,
	"Мыло жидкое" => 1,
	"Мыло натуральное" => 1,
	"Волос" => 1,
	"Для детей" => 1,
	"Лица" => 1,
	"Косметические наборы" => 1,
	"Эфирные масла" => 1,
	"Мыло авторской работы" => 1,
	"Классические шампуни" => 1,
	"Гидролаты" => 1,
	"Полости рта" => 1,
	"Тела" => 1,
	"Косметические масла" => 1,
	"Мука и масла" => 2,
	"Дезодоранты BIO" => 1,
	"Для кухни" => 1,
	"Для стирки и уборки" => 1,
	"Для стирки" => 1,
	"Натуральные освежители воздуха" => 1,
	"Шампуни и гели для душа «Для всей семьи»" => 1,
	"Интимная" => 1,
	"Мыло туалетное" => 1,
	"Органическая серия косметики Argan" => 1,
	"Зерна и семена" => "2,3",
	"Слинги и слингоодежда" => 1,
	"Натуральные чаи" => "2,3",
	"Кедровая продукция" => "1,2",
	"Декоративная косметика" => 1,
	"Полотенца" => 1,
	"Детские игрушки" => 1,
	"Урбеч" => "2,3",
	"Шунгит" => 1,
	"ЗДОРОВОЕ ПИТАНИЕ" => "2,3",
	"Для уборки дома" => 1,
	"Зёрна и семена" => "2,3",
	"Специи и приправы" => "2,3",
	"Приспособления" => 1,
	"Подушки" => 1,
	"Одеяла и пледы" => 1,
	"Защита от насекомых" => 1,
	"Мебель" => 1,
	"Подарки и сувениры" => 1,
	"Лучшее из Индии" => "1,2,3",
	"Книги и журналы" => 1,
	"Для сна и отдыха" => 1
	
	);
	
	/**
	 * @var связывает названия категорий naturlife с номерами категорий в ecostore. 
	 */
	const CAT_MAP = array(
	"Для бани, ванны и душа" => 2,
	"Мыло жидкое" => 2,
	"Мыло натуральное" => 2,
	"Волос" => 2,
	"Для детей" => 4,
	"Лица" => 2,
	"Косметические наборы" => 2,
	"Эфирные масла" => 2,
	"Мыло авторской работы" => 2,
	"Классические шампуни" => 2,
	"Гидролаты" => 2,
	"Полости рта" => 2,
	"Тела" => 2,
	"Косметические масла" => 2,
	"Мука и масла" => 1,
	"Дезодоранты BIO" => 2,
	"Для кухни" => 3,
	"Для стирки и уборки" => 3,
	"Для стирки" => 3,
	"Натуральные освежители воздуха" => 3,
	"Шампуни и гели для душа «Для всей семьи»" => 2,
	"Интимная" => 2,
	"Мыло туалетное" => 2,
	"Органическая серия косметики Argan" => 2,
	"Зерна и семена" => 1,
	"Слинги и слингоодежда" => 8,
	"Натуральные чаи" => 1,
	"Кедровая продукция" => 1,
	"Декоративная косметика" => 2,
	"Полотенца" => 3,
	"Детские игрушки" => 4,
	"Урбеч" => 1,
	"Шунгит" => 3,
	"ЗДОРОВОЕ ПИТАНИЕ" => 1,
	"Для уборки дома" => 3,
	"Зёрна и семена" => 1,
	"Специи и приправы" => 1
		
);
	
	/**
	 * @var связывает названия категорий naturlife с номерами подкатегорий в ecostore. 
	 */
	
	const SUBCAT_MAP = array(
	"Для бани, ванны и душа" => 14,
	"Мыло жидкое" => 70,
	"Мыло натуральное" => 70,
	"Волос" => 12,
	"Лица" => 16,
	"Эфирные масла" => 17,
	"Мыло авторской работы" => 70,
	"Классические шампуни" => 12,
	"Полости рта" => 9,
	"Тела" => 14,
	"Косметические масла" => 17,
	"Мука и масла" => 46,
	"Дезодоранты BIO" => 24,
	"Для стирки и уборки" => 68,
	"Для стирки" => 68,
	"Натуральные освежители воздуха" => 79,
	"Шампуни и гели для душа «Для всей семьи»" => 14,
	"Мыло туалетное" => 70,
	"Натуральные чаи" => 37,
	"Для уборки дома" => 80,
	"Зёрна и семена" => 28,
	"Зерна и семена" => 28,
	"Специи и приправы" => 41
		
);
	
	

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
		'Подкатегория' => 2.7,
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
			
			eval('$type = key_exists($product->category->name, self::TYPE_MAP) ? self::TYPE_MAP[$product->category->name] :
																			  1;'); //eval() для подавления ложной ошибки netBeans
			$activeSheet->setCellValue('B' . ($index + 2), $type); // Тип товара
			
			eval('$category = key_exists($product->category->name, self::CAT_MAP) ? self::CAT_MAP[$product->category->name] :
																			  $product->category->name;'); //eval() для подавления ложной ошибки netBeans
			$activeSheet->setCellValue('C' . ($index + 2), $category); // Категория
			eval('$subcategory = key_exists($product->category->name, self::SUBCAT_MAP) ? self::SUBCAT_MAP[$product->category->name] :
																			  "";'); //eval() для подавления ложной ошибки netBeans
			$activeSheet->setCellValue('D' . ($index + 2), $subcategory); // Подкатегория
			$activeSheet->setCellValue('E' . ($index + 2), sprintf(// Производитель
							"%s;%s;%s", $product->manufacturer->name, $product->manufacturer->country, $product->manufacturer->url));
			$activeSheet->setCellValue('F' . ($index + 2), $product->name); // Название 
			$activeSheet->setCellValue('G' . ($index + 2), $product->ingredients); // Состав
			$activeSheet->setCellValue('H' . ($index + 2), $product->shortDescr); // Краткое описание
			$activeSheet->setCellValue('I' . ($index + 2), $product->keywords); // Ключевые слова
			$activeSheet->setCellValue('J' . ($index + 2), $product->price); // Цена
			$activeSheet->setCellValue('K' . ($index + 2), $product->weight); // вес

			$this->writeImages($product, $index + 1, $imagesInDir, $imagesOutDir);
			
			$activeSheet->getRowDimension($index + 2)->setRowHeight(-1);
			
		}

//Сохраняем файл таблиц на диске
		$docWriter = new \PHPExcel_Writer_Excel2007($excelDoc);
		$docWriter->save($this->fileName);
	}

}

?>
