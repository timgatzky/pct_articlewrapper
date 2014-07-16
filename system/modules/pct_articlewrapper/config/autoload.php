<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package pct_autogrid
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'PCT\ArticleWrapper',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'PCT\ArticleWrapper\ArticleWrapper' 		=> 'system/modules/pct_articlewrapper/PCT/ArticleWrapper/ArticleWrapper.php',
	'PCT\ArticleWrapper\TableArticle' 			=> 'system/modules/pct_articlewrapper/PCT/ArticleWrapper/TableArticle.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'pct_articlewrapper_start'       	=> 'system/modules/pct_articlewrapper/templates',
	'pct_articlewrapper_stop'       	=> 'system/modules/pct_articlewrapper/templates',
	'pct_articlewrapper_single'       	=> 'system/modules/pct_articlewrapper/templates',
));
