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
	 * Load backend styles
	 * @param object
	 */
	public function loadAssets(\DataContainer $objDC)
	{
		$GLOBALS['TL_CSS'][] = 'system/modules/pct_articlewrapper/assets/css/be_styles.css';
	}
	
	/**
	 * Modify the DCA on load
	 * @param object
	 */
	public function modifyDca(\DataContainer $objDC)
	{
		$objActiveRecord = \Database::getInstance()->prepare("SELECT * FROM ".$objDC->table." WHERE id=?")->limit(1)->execute($objDC->id);
		
		// restrict layout sections
		if(is_array($GLOBALS['PCT_ARTICLEWRAPPER']['sections']) && count($GLOBALS['PCT_ARTICLEWRAPPER']['sections']) > 0)
		{
			if(!in_array($objActiveRecord->inColumn, $GLOBALS['PCT_ARTICLEWRAPPER']['sections']))
			{
				unset($GLOBALS['TL_DCA']['tl_article']['palettes']['articlewrapper_start']);
				unset($GLOBALS['TL_DCA']['tl_article']['palettes']['articlewrapper_stop']);
				unset($GLOBALS['TL_DCA']['tl_article']['fields']['articlewrapper']);
			}
			
			// remove the layout section select
			if(strlen($objActiveRecord->articlewrapper) > 0)
			{
				$GLOBALS['TL_DCA']['tl_article']['fields']['inColumn']['eval']['readonly'] = 1;
				unset($GLOBALS['TL_DCA']['tl_article']['fields']['inColumn']);
			}
		}
		
		// remove autogrid stuff
		if(strlen($objActiveRecord->articlewrapper) > 0 && in_array('pct_autogrid', \Config::getInstance()->getActiveModules()))
		{
			unset($GLOBALS['TL_DCA']['tl_article']['fields']['autogrid']);
		}
		
		if(in_array('pct_autogrid', \Config::getInstance()->getActiveModules()))
		{
			$GLOBALS['TL_DCA']['tl_article']['list']['operations']['autogrid']['button_callback'] = array('PCT\ArticleWrapper\TableArticle','autogridButton');
		}
	}
	
	
	/**
	 * Modify the list view
	 * @param array
	 * @param string
	 * @return string
	 */
	public function listView($row, $label)
	{
		$this->loadDataContainer('tl_article');
		$helper = new \tl_article();
		
		$strBuffer = $helper->addIcon($row, $label);
		
		if($row['articlewrapper'] == 'articlewrapper_start')
		{
			$strBuffer = '<div class="'.$row['articlewrapper'].' '.$row['inColumn'].'">'.$strBuffer.'</div>';
			$GLOBALS['PCT_ARTICLEWRAPPER']['wrapperOpen'] = true;
		}
	
		else if($row['articlewrapper'] == 'articlewrapper_stop')
		{
			$strBuffer = '<div class="'.$row['articlewrapper'].' '.$row['inColumn'].'">'.$strBuffer.'</div>';
			$GLOBALS['PCT_ARTICLEWRAPPER']['wrapperOpen'] = false;
		}
		
		else if($row['articlewrapper'] == 'articlewrapper_single')
		{
			$strBuffer = '<div class="'.$row['articlewrapper'].' '.$row['inColumn'].'">'.$strBuffer.'</div>';
		}
		
		else if($GLOBALS['PCT_ARTICLEWRAPPER']['wrapperOpen'])
		{
			$strBuffer = '<div class="articlewrapper_indent between '.$row['inColumn'].'">'.$strBuffer.'</div>';
		}
		
		return $strBuffer;
	}
	
	
	/**
	 * Remove the edit button for article wrappers
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editButton($row, $href, $label, $title, $icon, $attributes)
	{
		$this->loadDataContainer('tl_article');
		$helper = new \tl_article();
		if(strlen($row['articlewrapper']) < 1 || $row['articlewrapper'] == 'articlewrapper_single')
		{
			return $helper->editArticle($row, $href, $label, $title, $icon, $attributes);
		}
		return '';
	}
	
	
	/**
	 * Remove the autogrid button for article wrappers
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function autogridButton($row, $href, $label, $title, $icon, $attributes)
	{
		$helper = new \PCT\AutoGrid\TableArticle();
		if(strlen($row['articlewrapper']) < 1)
		{
			return $helper->toggleAutoGridIcon($row, $href, $label, $title, $icon, $attributes);
		}
		return '';
	}
}