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
 * ArticleWrapper
 */
class ArticleWrapper
{
	/**
	 * Include css on init
	 */
	public function __construct()
	{
		if(TL_MODE == 'FE')
		{
			$GLOBALS['TL_CSS'][] = $GLOBALS['PCT_ARTICLEWRAPPER']['css'];
		}
	}
	
	/**
	 * Inject autowrapper classes in articles
	 * @param object
	 * @reuturn object
	 */
	public function getArticlesCallback($objArticle)
	{
		if(TL_MODE != 'FE' )
		{
			return $objArticle;
		}
		
		// handle non articlewrapper articles
		if(!$objArticle->articlewrapper)
		{
			$classes = (is_array($objArticle->classes) ? $objArticle->classes : array());
	
			$objBefore = $this->fetchSiblingArticleBefore($objArticle->id);
			if($objBefore->articlewrapper == 'articlewrapper_start')
			{
				$classes[] = 'first';
			}
			
			$objNext = $this->fetchSiblingArticleNext($objArticle->id);
			if($objNext->articlewrapper == 'articlewrapper_stop')
			{
				$classes[] = 'last';
			}
			$objArticle->classes = array_unique($classes);
			return $objArticle;
		}
		
		$collection = is_array($GLOBALS['PCT_ARTICLEWRAPPER']['collection'][$objArticle->pid][$objArticle->inColumn]) ? $GLOBALS['PCT_ARTICLEWRAPPER']['collection'][$objArticle->pid][$objArticle->inColumn] : array();
		$collection[] = $objArticle->id;
		
		$classes = (is_array($objArticle->classes) ? $objArticle->classes : array());
		$classes[] = 'articlewrapper-id_'.$objArticle->id;
		
		// add classes to wrapper article
		foreach($collection as $i => $id)
		{
			if($objArticle->id != $id) {continue;}
			if($i == 0)
			{
				$classes[] = 'first';
			}
			if($i >= count($arrCollection) - 1)
			{
				$classes[] = 'last';
			}
			($i%2 == 0 ? $classes[] = 'even' : $classes[] = 'odd');
		}
		
		$objArticle->classes = $classes;
		
		// store couple data for further use
		$GLOBALS['PCT_ARTICLEWRAPPER']['ident'][$objArticle->id] = $objArticle;
		$GLOBALS['PCT_ARTICLEWRAPPER']['collection'][$objArticle->pid][$objArticle->inColumn] = $collection;
		
		return $objArticle;
	}
	
	
	/**
	 * Create a wrapping div around the grid elements
	 * @param string
	 * @param string
	 * @return string
	 */
	public function wrapArticles($strBuffer, $strTemplate)
	{
		if(TL_MODE != 'FE')
		{
			return $strBuffer;
		}
		
		$preg = preg_match('/class="(.*?)\"/', $strBuffer,$result);
		if(!$preg)
		{
			return $strBuffer;
		}
		
		$classes = explode(' ', $result[1]);
		$article = 0;
		foreach($classes as $class)
		{
			if(strlen(strpos($class,'articlewrapper-id')))
			{
				$tmp = explode('_',$class);
				$article = $tmp[1];
			}
		}
		
		// return
		if($article < 1)
		{
			return $strBuffer;
		}
		
		// fetch the article
		$objArticle = $GLOBALS['PCT_ARTICLEWRAPPER']['ident'][$article];
		
		$cssID = 'id="'.($objArticle->alias ? $objArticle->alias : 'article-'.$objArticle->id).'"';
		$arrCssID = deserialize($objArticle->cssID);
		if($arrCssID[0])
		{
			$cssID = 'id="'.$arrCssID[0].'"';
		}
		
		$classes = explode(' ', $arrCssID[1]);
		$classes = array_merge($classes,$objArticle->classes);
		$classes[] = 'articlewrapper';
		$classes[] = $objArticle->articlewrapper_style;
		$classes[] = $objArticle->articlewrapper_layout;
		$classes[] = 'articlewrapper_'.$objArticle->id;
		
		// remove the identifier
		unset($classes[array_search('articlewrapper-id_'.$article, $classes)]);
		
		$classes = array_unique(array_filter($classes,'strlen'));
		
		// margin
		$margin = deserialize($objArticle->space);
		$styles = array();
		if(strlen($margin[0]) > 0)
		{
			$styles[] = 'margin-top:'.$margin[0].'px;';
		}
		if(strlen($margin[1]) > 0)
		{
			$styles[] = 'margin-bottom:'.$margin[1].'px;';
		}
		
		// padding
		$padding = deserialize($objArticle->articlewrapper_padding);
		if(strlen($padding[0]) > 0)
		{
			$styles[] = 'padding-top:'.$padding[0].'px;';
		}
		if(strlen($padding[1]) > 0)
		{
			$styles[] = 'padding-bottom:'.$padding[1].'px;';
		}
		
		
		$isStart = true;
		$strTemplate = 'pct_articlewrapper_start';
		if($objArticle->articlewrapper == 'articlewrapper_stop')
		{
			$isStart = false;
			$strTemplate = 'pct_articlewrapper_stop';
		}
		
		$objTemplate = new \FrontendTemplate($strTemplate);
		$objTemplate->setData($objArticle->row());
		$objTemplate->cssID = $cssID;
		$objTemplate->class = trim(implode(' ', $classes));
		$objTemplate->styles = trim(implode(' ', $styles));
		
		$strBuffer = $objTemplate->parse();
		
		unset($GLOBALS['PCT_ARTICLEWRAPPER']['ident'][$article]);
		
		return $strBuffer;
	}
	
	
	/**
	 * Fetch sibling article right before
	 * @param integer
	 * @return object	DatabaseResult
	 */
	protected function fetchSiblingArticleBefore($intId)
	{
		$strWhere = "WHERE id!=".$intId." 
			AND pid=(SELECT pid FROM tl_article WHERE id=".$intId.")
			AND inColumn=(SELECT inColumn FROM tl_article WHERE id=".$intId.") 
			AND published=1
			AND (sorting < (SELECT sorting FROM tl_article WHERE id=".$intId."))
			AND (start = '' OR start < UNIX_TIMESTAMP()) AND (stop = '' OR stop > UNIX_TIMESTAMP() )
		";
		
		// fetch sibling articles
		return \Database::getInstance()->prepare("SELECT * FROM tl_article ".$strWhere." ORDER BY sorting DESC")->limit(1)->execute();
	}
	
	
	/**
	 * Fetch sibling article below
	 * @param integer
	 * @return object	DatabaseResult
	 */
	protected function fetchSiblingArticleNext($intId)
	{
		$strWhere = "WHERE id!=".$intId."
			AND pid=(SELECT pid FROM tl_article WHERE id=".$intId.")
			AND inColumn=(SELECT inColumn FROM tl_article WHERE id=".$intId.") 
			AND published=1
			AND (sorting > (SELECT sorting FROM tl_article WHERE id=".$intId."))
			AND (start = '' OR start < UNIX_TIMESTAMP()) AND (stop = '' OR stop > UNIX_TIMESTAMP() )
		";
		
		// fetch sibling articles
		return \Database::getInstance()->prepare("SELECT * FROM tl_article ".$strWhere." ORDER BY sorting")->limit(1)->execute();
	}

}