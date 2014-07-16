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

/**
 * Constants
 */
define(PCT_ARTICLEWRAPPER_VERSION, '1.0.0');

/**
 * Globals
 */
$GLOBALS['PCT_ARTICLEWRAPPER']						= array();
$GLOBALS['PCT_ARTICLEWRAPPER']['css']				= 'system/modules/pct_articlewrapper/assets/css/styles.css';
$GLOBALS['PCT_ARTICLEWRAPPER']['wrapperOpen']		= false;	
$GLOBALS['PCT_ARTICLEWRAPPER']['siblings']			= array();
$GLOBALS['PCT_ARTICLEWRAPPER']['sections']			= array('main');

/**
 * Hooks
 */
#$GLOBALS['TL_HOOKS']['loadDataContainer'][] 	= array('PCT\AutoGrid\DcaHelper','onLoadDataContainer');
$GLOBALS['TL_HOOKS']['getArticle'][] 			= array('PCT\ArticleWrapper\ArticleWrapper','getArticlesCallback');
$GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = array('PCT\ArticleWrapper\ArticleWrapper','wrapArticles');