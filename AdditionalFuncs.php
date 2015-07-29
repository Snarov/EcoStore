<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

/**
 * @file
 * Файл содержит дополнительные функции, необходимые для работы скрипта, которые не вошли ни в один класс
 */

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
 * Многовбайтовый аналог substr_replace()
 * Позаимствовано с php.net. Примем на веру, то что функция работает
 */
if (!function_exists("mb_substr_replace")) {

	function mb_substr_replace($string, $replacement, $start, $length = null, $encoding = null) {
		if ($encoding == null) {
			$encoding = mb_internal_encoding();
		}
		if ($length == null) {
			return mb_substr($string, 0, $start, $encoding) . $replacement;
		} else {
			if ($length < 0)
				$length = mb_strlen($string, $encoding) - $start + $length;
			return
					mb_substr($string, 0, $start, $encoding) .
					$replacement .
					mb_substr($string, $start + $length, mb_strlen($string, $encoding), $encoding);
		}
	}

}

/**
 * Производит расстановку переносов в строке
 * 
 * @param string $str Строка, в которой расставляются переносы
 * @param int $maxLen Максимальное количество символов в строке
 * 
 * @retval string Строка, с расставленными переносами. Если length неположительный, то возвращает неизмененную строку
 */
function placeLineBreaks($str, $maxLen) {
	// функция ищет пробел, котороый отдален от начала очередной строки как можно дальше но но не дальше чем на $maxlen и
	// заменяет его символом перевода строки

	if ($maxLen > 0) {
		$prevSpacePos = 0;
		$lastNewLinePos = -1;
//		$newLinesCount = 0;

		while (true) {

			$lastSpacePos = mb_strpos($str, ' ', $prevSpacePos + 1);

			if ($lastSpacePos !== false) {
				if ($lastSpacePos - $lastNewLinePos - 1 > $maxLen) {

					$str = mb_substr_replace($str, "\n", $prevSpacePos, 1);
					$lastNewLinePos = $prevSpacePos;
				}

				$prevSpacePos = $lastSpacePos;
			} else {
				if (mb_strlen($str) - $lastNewLinePos - 1 > $maxLen) {
					$str = mb_substr_replace($str, "\n", $prevSpacePos, 1);
				}
				break;
			}
		}
	}

	return $str;
}

/**
 * Процедура скачивает все картинки из $images на локальную машину. Если картинка существует то она не скачивается
 * 
 * @param string $URL папка, в которой лежат скачиваемые картинки
 * @param string $downloadPath
 * @param Image[] $images
 * 
 */
function downloadImages($URL, $downloadPath, array $images) {
	mkdir($downloadPath);
	foreach ($images as $image) {
		if (!file_exists($downloadPath . '/' . $image->path)) {
			copy($URL . '/' . $image->path, $downloadPath . '/' . $image->path);
		}
	}
}

/**
 * Computes base root, base path, and base url.
 * 
 * This code is adapted from Drupal function conf_init, see:
 * http://api.drupal.org/api/drupal/includes%21bootstrap.inc/function/conf_init/6
 * 
 */
function htmltodocx_paths() {

	if (!isset($_SERVER['SERVER_PROTOCOL']) || ($_SERVER['SERVER_PROTOCOL'] != 'HTTP/1.0' && $_SERVER['SERVER_PROTOCOL'] != 'HTTP/1.1')) {
		$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.0';
	}

	if (isset($_SERVER['HTTP_HOST'])) {
		// As HTTP_HOST is user input, ensure it only contains characters allowed
		// in hostnames. See RFC 952 (and RFC 2181).
		// $_SERVER['HTTP_HOST'] is lowercased here per specifications.
		$_SERVER['HTTP_HOST'] = strtolower($_SERVER['HTTP_HOST']);
		if (!htmltodocx_valid_http_host($_SERVER['HTTP_HOST'])) {
			// HTTP_HOST is invalid, e.g. if containing slashes it may be an attack.
			header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
			exit;
		}
	} else {
		// Some pre-HTTP/1.1 clients will not send a Host header. Ensure the key is
		// defined for E_ALL compliance.
		$_SERVER['HTTP_HOST'] = '';
	}

	// Create base URL
	$base_root = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';

	$base_url = $base_root .= '://' . $_SERVER['HTTP_HOST'];

	// $_SERVER['SCRIPT_NAME'] can, in contrast to $_SERVER['PHP_SELF'], not
	// be modified by a visitor.
	if ($dir = trim(dirname($_SERVER['SCRIPT_NAME']), '\,/')) {
		$base_path = "/$dir";
		$base_url .= $base_path;
		$base_path .= '/';
	} else {
		$base_path = '/';
	}

	return array(
		'base_path' => $base_path,
		'base_url' => $base_url,
		'base_root' => $base_root,
	);
}

/**
 * Check for valid http host.
 * 
 * This code is adapted from function drupal_valid_http_host, see:
 * http://api.drupal.org/api/drupal/includes%21bootstrap.inc/function/drupal_valid_http_host/6
 * 
 * @param mixed $host
 * @return int
 */
function htmltodocx_valid_http_host($host) {
	return preg_match('/^\[?(?:[a-z0-9-:\]_]+\.?)+$/', $host);
}

