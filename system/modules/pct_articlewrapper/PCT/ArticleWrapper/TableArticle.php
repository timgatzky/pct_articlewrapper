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
 * Namespace
 */
namespace PCT\ArticleWrapper;

/**
 * Class file
 * TableArticle
 */
class TableArticle extends \Backend
{
	/**
	 * Return all article types as array
	 * @param object
	 * @return array
	 */
	public function getArticleTypes(\DataContainer $objDC)
	{
		return array('articlewrapper');
	}
}