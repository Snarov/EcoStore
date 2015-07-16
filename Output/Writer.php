<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Output;

/**
 * Класс, наследуемый классами, которые производят запись выходных данных в файл
 * @author snarov
 * @package \Output
 */
abstract class Writer {

	protected $fileName;

	function __construct($fileName) {
		$this->fileName = $fileName;
	}

	/**
	 * перемещает картинки товара в папку images  и переимновывает их. (имя файла :
	 * номер_товара-порядковый_номер_картинки.jpg (png, gif))
	 * 
	 * @param Product $product
	 * @param int $productNum
	 * @param string $inDir
	 * @param string $outDir
	 */
	protected function writeImages($product, $productNum, $inDir, $outDir) {


		foreach ($product->images as $index => $image) {


			$tmpArr = explode('.', $image->path);
			$formatName = $tmpArr[count($tmpArr) - 1];

			if (!copy("$inDir/{$image->path}", "$outDir/" . sprintf("%d-%d.%s", $productNum, $index + 1, $formatName))) {
				echo NOTICE . " {$image->path}: " . IMG_COPY_FAIL . "\n";
			}
		}
	}

	abstract function write(array $products, $imagesInDir, $imagesOutDir);
}
