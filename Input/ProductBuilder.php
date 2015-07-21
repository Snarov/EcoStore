<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Input;

require_once 'AdditionalFuncs.php';
require_once 'Entities/Product.php';

use Entities\Product;

/**
 * Строит объекты Product на основе экземпляров классов, для которых класс Product является классом-контейнером: Manufacturer,
 * Category, Image - и объектов, описывающих связи между этими объектами (таблицы связи "многие ко многим" из БД магазина), а 
 * также сырыми объектами типа "Товар". Сырые объекты, не соответствующие схеме "Товар" из БД магазина игнорируются. объекты связи,
 * не соответсвующие схеме "Связь между отношениями" в БД магазина игнорируются.
 *
 * @author snarov
 * @package \Input
 */
class ProductBuilder {

	/**
	 * @param Manufacturer[] $manufacturers
	 * @param Category[] $categories
	 * @param Image[] $images
	 * @param object[] $productManufacturerBinds
	 * @param object[] $productCategoryBinds
	 * @param object[] $productImageBinds
	 * @param object[] $productPrices
	 * @param object[] $objects
	 * 
	 * @return Product[] 
	 */
	function buildAll(
			array $manufacturers,
			array $categories,
			array $images,
			array $productManufacturerBinds,
			array $productCategoryBinds,
			array $productImageBinds,
			array $productPrices,
			array $objects) {
		
		$retval = array();
		//Задаем имена свойств, выражающих связи: поле со значением родительского элемента и поле со значением дочернего
		
		$categoryBindParentFieldName = getPropertyNameByNum($productCategoryBinds[0], 1);
		$categoryBindChildFieldName = getPropertyNameByNum($productCategoryBinds[0], 2);
		
		$manufacturerBindParentFieldName = getPropertyNameByNum($productManufacturerBinds[0], 1);
		$manufacturerBindChildFieldName = getPropertyNameByNum($productManufacturerBinds[0], 2);
		
		$imageBindParentFieldName = getPropertyNameByNum($productImageBinds[0], 1);
		$imageBindChildFieldName = getPropertyNameByNum($productImageBinds[0], 2);
		
		$priceProductIdFieldName = getPropertyNameByNum($productPrices[0], 1);
		$pricePriceFieldName = getPropertyNameByNum($productPrices[0], 3);
		
		
		foreach ($objects as $object) {
			if (property_exists($object, 'virtuemart_product_id')
					&& property_exists($object, 'product_s_desc')
					&& property_exists($object, 'product_desc')
					&& property_exists($object, 'product_name')) {
				
				$product = new Product($object->virtuemart_product_id);

				$product->name = $object->product_name;
				$product->shortDescr = strip_tags($object->product_s_desc);
				$product->fullDescr = preg_replace('/<img.*>/', '', $object->product_desc);
				if(strpos($product->fullDescr, "<img")){
					echo 'lol';
				}

				//ищем объект связанной категории
				$categoryBind = findByProperty($productCategoryBinds, $categoryBindParentFieldName,	$product->id);
		 		
				if($categoryBind !== null){
					$product->category = findByProperty($categories, 'id', $categoryBind->$categoryBindChildFieldName);
				}else{
					echo NOTICE . " товар № {$product->id}: " . NO_CATEGORY. "\n";
				}
								
				//ищем объект связанного поставщика
				$manufacturerBind = findByProperty($productManufacturerBinds,$manufacturerBindParentFieldName, $product->id);
				
				if($manufacturerBind !== null){
					$product->manufacturer = findByProperty($manufacturers, 'id', $manufacturerBind->$manufacturerBindChildFieldName);
				}else{
					echo NOTICE . " товар № {$product->id}: " . NO_MANUFACTURER . "\n";
				}
				
				//ищем объекты связанных картинок
				$imageBinds = findAllByProperty($productImageBinds, $imageBindParentFieldName, $product->id);
				
				foreach($imageBinds as $imageBind){
					$product->addImage(findByProperty($images, 'id', $imageBind->$imageBindChildFieldName));
				}
				
				//задаем цену продукта
				$productPrice = findByProperty($productPrices, $priceProductIdFieldName, $product->id);
				
				if($productPrice !== null){
					$product->price = $productPrice->$pricePriceFieldName;
				}
			}
			
			/**
			 * @todo что делать с полями Product::ingredients, Product::keywords, Product::sale, Product::otherSpecs ???
			 */
			
			$retval[] = $product;
		}
		
		return $retval;
	}

}
