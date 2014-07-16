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
	 * Modify the DCA on load
	 * @param object
	 */
	public function modifyDca(\DataContainer $objDC)
	{
		$objActiveRecord = \Database::getInstance()->prepare("SELECT * FROM ".$objDC->table." WHERE id=?")->limit(1)->execute($objDC->id);
		
		if(is_array($GLOBALS['PCT_ARTICLEWRAPPER']['sections']) && count($GLOBALS['PCT_ARTICLEWRAPPER']['sections']) > 0)
		{
			// filter by section
			if(!in_array($objActiveRecord->inColumn, $GLOBALS['PCT_ARTICLEWRAPPER']['sections']))
			{
				unset($GLOBALS['TL_DCA']['tl_article']['palettes']['articlewrapper_start']);
				unset($GLOBALS['TL_DCA']['tl_article']['palettes']['articlewrapper_stop']);
				unset($GLOBALS['TL_DCA']['tl_article']['fields']['articlewrapper']);
			}
		}
		
		// remove autogrid stuff
		if(strlen($objActiveRecord->articlewrapper) > 0 && in_array('pct_autogrid', \Config::getInstance()->getActiveModules()))
		{
			unset($GLOBALS['TL_DCA']['tl_article']['fields']['autogrid']);
		}
	}
}