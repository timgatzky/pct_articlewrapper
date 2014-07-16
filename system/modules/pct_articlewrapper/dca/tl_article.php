<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @copyright	Tim Gatzky 2014
 * @author		Tim Gatzky <info@tim-gatzky.de>
 * @package		pct_articlewrapper
 * @link		http://contao.org
 */

$GLOBALS['TL_DCA']['tl_article']['config']['onload_callback'][] = array('PCT\ArticleWrapper\TableArticle', 'loadAssets');
$GLOBALS['TL_DCA']['tl_article']['config']['onload_callback'][] = array('PCT\ArticleWrapper\TableArticle', 'modifyDca');

/**
 * List
 */
$GLOBALS['TL_DCA']['tl_article']['list']['label']['label_callback'] = array('PCT\ArticleWrapper\TableArticle','listView');

/**
 * Buttons
 */
$GLOBALS['TL_DCA']['tl_article']['list']['operations']['edit']['button_callback'] = array('PCT\ArticleWrapper\TableArticle','editButton');
		
/**
 * Selector
 */
$GLOBALS['TL_DCA']['tl_article']['palettes']['__selector__'][] = 'articlewrapper';

/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_article']['palettes']['articlewrapper_start'] = '{articlewrapper_legend},articlewrapper,articlewrapper_style,articlewrapper_padding,articlewrapper_layout;{title_legend},title,alias;{layout_legend},inColumn;{expert_legend:hide},cssID,space;{publish_legend},published';
$GLOBALS['TL_DCA']['tl_article']['palettes']['articlewrapper_stop'] = '{articlewrapper_legend},articlewrapper;{title_legend},title,alias;{layout_legend},inColumn;{expert_legend:hide},space;{publish_legend},published';

$GLOBALS['TL_DCA']['tl_article']['palettes']['default'] = '{articlewrapper_legend},articlewrapper,articlewrapper_style,articlewrapper_padding,articlewrapper_layout;'.$GLOBALS['TL_DCA']['tl_article']['palettes']['default'];

/**
 * Fields
 */
array_insert($GLOBALS['TL_DCA']['tl_article']['fields'],0,array
(
	'articlewrapper'	=> array
	(
		'label'					=> &$GLOBALS['TL_LANG']['tl_article']['articlewrapper'],
		'exclude'				=> true,
		'default'				=> (\Input::get('type') == 'articlewrapper' ? 'articlewrapper_single' : ''),
		'inputType'				=> 'select',
		'options'				=> array('articlewrapper_single','articlewrapper_start','articlewrapper_stop'),
		'reference'				=> &$GLOBALS['TL_LANG']['tl_article']['articlewrapper'],
		'eval'					=> array('tl_class'=>'clr','includeBlankOption'=>true,'chosen'=>true,'submitOnChange'=>true),
		'sql'					=> "varchar(32) NOT NULL default ''",
	),
	'articlewrapper_style'	=> array
	(
		'label'					=> &$GLOBALS['TL_LANG']['tl_article']['articlewrapper_style'],
		'exclude'				=> true,
		'inputType'				=> 'select',
		'options'				=> array('fullwidth','fullwidth_contentboxed','boxed'),
		'reference'				=> $GLOBALS['TL_LANG']['tl_article']['articlewrapper_style'],
		'eval'					=> array('tl_class'=>'w50','chosen'=>true),
		'sql'					=> "varchar(32) NOT NULL default ''",
	),
	'articlewrapper_layout'	=> array
	(
		'label'					=> &$GLOBALS['TL_LANG']['tl_article']['articlewrapper_layout'],
		'exclude'				=> true,
		'inputType'				=> 'select',
		'options'				=> ($GLOBALS['PCT_ARTICLEWRAPPER']['layout_options'] ? $GLOBALS['PCT_ARTICLEWRAPPER']['layout_options'] : array()),
		'reference'				=> $GLOBALS['TL_LANG']['pct_articlewrapper']['layout_options'],
		'eval'					=> array('tl_class'=>'w50','chosen'=>true),
		'sql'					=> "varchar(32) NOT NULL default ''",
	),
	'articlewrapper_padding'	=> array
	(
		'label'					=> &$GLOBALS['TL_LANG']['tl_article']['articlewrapper_padding'],
		'exclude'				=> true,
		'inputType'				=> 'text',
		'eval'					=> array('multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50'),
		'sql'					=> "varchar(64) NOT NULL default ''"
	),
));

if(count($GLOBALS['PCT_ARTICLEWRAPPER']['sections']) > 0)
{
	$GLOBALS['TL_DCA']['tl_article']['fields']['inColumn']['eval']['submitOnChange'] = true;
}

/**
 * Insert articlewrapper button in global operation
 */
array_insert($GLOBALS['TL_DCA']['tl_article']['list']['global_operations'],0,array
(
	'articlewrapper'=> array
	(
		'label'			=> &$GLOBALS['TL_LANG']['tl_article']['header_articlewrapper'],
		'href'			=> '&amp;act=paste&amp;mode=create&amp;type=articlewrapper',
		'icon'			=> 'new.gif',
		'class'			=> 'header_articlewrapper'
	)
));