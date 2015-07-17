<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

//Файл содержит дополнительные функции, необходимые для работы скрипта, которые не вошли ни в один класс

/**
 * Ищет в массиве объектов объект по значению свойства, заданного его именем. Для корректного выполнения функции
 *  все объекты в $objects должны быть одного типа
 * 
 * @param array $objects Массив, в котором производится поиск
 * @param int $propertyName Имя свойства, по которому ведется поиск
 * @param mixed $value Искомое значение свойства
 *  
 * @return object Объект, удовлетворяющий критериям поиска, либо null, если найти не удалось
 */
function findByProperty(array $objects, $propertyName, $value) {
	$retval = null;

	foreach ($objects as $object) {
		if ($object->$propertyName === $value) {
			$retval = $object;
			break;
		}
	}

	return $retval;
}

/**
 * Ищет в массиве объектов все объекты по значению свойства, заданного его именем. Для корректного выполнения функции
 *  все объекты в $objects должны быть одного типа
 * 
 * @param array $objects Массив, в котором производится поиск
 * @param int $propertyName Имя свойства, по которому ведется поиск
 * @param mixed $value Искомое значение свойства
 *  
 * @return array Объект, удовлетворяющий критериям поиска, либо пустой массив, если найти не удалось
 */
function findAllByProperty(array $objects, $propertyName, $value) {
	$retval = array();

	foreach ($objects as $object) {
		if ($object->$propertyName === $value) {
			$retval[] = $object;
		}
	}

	return $retval;
}

/**
 *  
 * Позволяет получить имя свойства объекта, по порядковому номеру этого свойства
 * 
 * @param object $object
 * @param type $propertyNum Порядковый номер свойства. Номера начинаются с 0
 * 
 * @retval string Имя свойства, либо пустая строка если свойства с таким номером не существует
 */
function getPropertyNameByNum($object, $propertyNum) {
	//получаем имя свойства с номером $propertyNum. 
	$propertyName = '';
	$properties = array_keys((array) $object); // Для этого кастуем объект в ассоциативный массив и получаем список
	// ключей этого массива
	if ($propertyNum >= 0 && $propertyNum < count($properties)) {
		$propertyNameParts = explode("\0", $properties[$propertyNum]); //Разделяем имя свойства на части
		$propertyName = $propertyNameParts[count($propertyNameParts) - 1]; //Последняя часть - имя свойства, используемое 
		//для получения его значения
	}

	return $propertyName;
}

/** 
 * @global array $translit
 * Таблица транслитерации ГОСТ 7.79-2000
 */
 $translit = array(
   
            'а' => 'a',   'б' => 'b',   'в' => 'v',
  
            'г' => 'g',   'д' => 'd',   'е' => 'e',
  
            'ё' => 'yo',   'ж' => 'zh',  'з' => 'z',
  
            'и' => 'i',   'й' => 'j',   'к' => 'k',
  
            'л' => 'l',   'м' => 'm',   'н' => 'n',
  
            'о' => 'o',   'п' => 'p',   'р' => 'r',
  
            'с' => 's',   'т' => 't',   'у' => 'u',
  
            'ф' => 'f',   'х' => 'x',   'ц' => 'c',
  
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'shh',
  
            'ь' => '\'',  'ы' => 'y',   'ъ' => '\'\'',
  
            'э' => 'e\'',   'ю' => 'yu',  'я' => 'ya',
          
  
            'А' => 'A',   'Б' => 'B',   'В' => 'V',
  
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
  
            'Ё' => 'YO',   'Ж' => 'Zh',  'З' => 'Z',
  
            'И' => 'I',   'Й' => 'J',   'К' => 'K',
  
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
  
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
  
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
  
            'Ф' => 'F',   'Х' => 'X',   'Ц' => 'C',
  
            'Ч' => 'CH',  'Ш' => 'SH',  'Щ' => 'SHH',
  
            'Ь' => '\'',  'Ы' => 'Y\'',   'Ъ' => '\'\'',
  
            'Э' => 'E\'',   'Ю' => 'YU',  'Я' => 'YA',
  
        );
  
      
      
