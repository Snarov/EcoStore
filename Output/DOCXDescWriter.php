<?php

/*
 * ООО "ТК ЭЛЬДОРАДО"
 * Витебск 2015 * 
 * Автор: Снаров И.А.
 */

namespace Output;

require_once 'PHPWord.php';
require_once 'PHPWord/IOFactory.php';
require_once 'simple_html_dom.php';
require_once 'h2d_htmlconverter.php';
require_once 'AdditionalFuncs.php';
require_once 'Writer.php';

/**
 * Вспомогательный класс, производящий запись полных описаний товаров в файл формата .docx. Производит преобразование из html в docx
 *
 * @author snarov
 * @package \Output
 */
class DOCXDescWriter extends Writer {

	/**
	 * @var array Стиль шрифта для заголовка описания
	 */
	const DESCR_HEADER_FONT = array('name' => 'Calibri', 'size' => 11, 'color' => '000000', 'bold' => true);

	/**
	 * @var array Стиль шрифта описания 
	 */
	const DESCR_FONT = array('name' => 'Calibri', 'size' => 12, 'color' => '000000', 'bold' => false);

	/**
	 * Записывает полное описание продуктов
	 * @param Products[] $products
	 */
	function writeDesc($products) {
		$wordDoc = new \PHPWord();
		$section = $wordDoc->createSection();
		$paths = htmltodocx_paths();

		// Массив настроек, позаимствованый из документации к 'HTML to DOCX converter'
		$settings = array(
			// Required parameters:
			'phpword_object' => &$wordDoc, // Must be passed by reference.
			// 'base_root' => 'http://test.local', // Required for link elements - change it to your domain.
			// 'base_path' => '/htmltodocx/documentation/', // Path from base_root to whatever url your links are relative to.
			'base_root' => $paths['base_root'],
			'base_path' => $paths['base_path'],
			// Optional parameters - showing the defaults if you don't set anything:
			'current_style' => self::DESCR_FONT, // The PHPWord style on the top element - may be inherited by descendent elements.
			'parents' => array(0 => 'body'), // Our parent is body.
			'list_depth' => 0, // This is the current depth of any current list.
			'context' => 'section', // Possible values - section, footer or header.
			'pseudo_list' => TRUE, // NOTE: Word lists not yet supported (TRUE is the only option at present).
			'pseudo_list_indicator_font_name' => 'Wingdings', // Bullet indicator font.
			'pseudo_list_indicator_font_size' => '7', // Bullet indicator size.
			'pseudo_list_indicator_character' => 'l ', // Gives a circle bullet point with wingdings.
			'table_allowed' => TRUE, // Note, if you are adding this html into a PHPWord table you should set this to FALSE: tables cannot be nested in PHPWord.
			'treat_div_as_paragraph' => TRUE, // If set to TRUE, each new div will trigger a new line in the Word document.
		);

		foreach ($products as $index => $product) {
			//Записываем только продукты, для которых задано полное описание
			if ($product->fullDescr) {
				$html = $product->fullDescr;
				$htmlDom = new \simple_html_dom();
				$htmlDom->load('<html><body>' . $html . '</body></html>');
				$htmlDomArray = $htmlDom->find('html', 0)->children();

				$section->addText(
						htmlspecialchars(
								'Товар № ' . ($index + 1)
						),
						self::DESCR_HEADER_FONT
				);
				htmltodocx_insert_html($section, $htmlDomArray[0]->nodes, $settings);

				$htmlDom->clear();
				unset($htmlDom);
				
			}
		}
		
		$objWriter = \PHPWord_IOFactory::createWriter($wordDoc, 'Word2007');
		$objWriter->save($this->fileName);
	}

}
