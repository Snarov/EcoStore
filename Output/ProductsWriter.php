<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Output;

require_once 'libs/SimpleImage.php';

/**
 * Класс, наследуемый классами, которые производят запись выходных данных о товарах в файл
 *
 * @author snarov
 * @package \Output
 */
abstract class ProductsWriter extends Writer {

	/**
	 * перемещает картинки товара в папку images  и переимновывает их, а так же изменяет их размер так, чтобы они были квадратными.
	 * Сторона квадрата выходной картинки равна длине наибольшей стороны исходной картинки.
	 * . (имя файла : номер_товара-порядковый_номер_картинки.jpg (png, gif))
	 * 
	 * @param Product $product
	 * @param int $productNum
	 * @param string $inDir
	 * @param string $outDir
	 */
	protected function writeImages($product, $productNum, $inDir, $outDir) {

		foreach ($product->images as $index => $image) {

			$extension = strrchr($image->path, '.');

			$simpleImage = new \SimpleImage();
			$simpleImage->load("$inDir/{$image->path}");
			if ($simpleImage->image) {
				$simpleImage->toSquare();
				$simpleImage->save("$outDir/" . sprintf("%d-%d%s", $productNum, $index + 1, $extension),$simpleImage->image_type);
			}else{
				echo NOTICE . " {$image->path}: " . IMG_COPY_FAIL . "\n";
			}
		}
	}

	abstract function write(array $products, $imagesInDir, $imagesOutDir);
}
