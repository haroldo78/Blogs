<?php

/**
 * @package		Rich Snippets Vote - Plugin for Joomla!
 * @author		DeConf - http://deconf.com
 * @copyright	Copyright (c) 2010 - 2012 DeConf.com
 * @license		GNU/GPL license: http://www.gnu.org/licenses/gpl-2.0.html
 */
 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );

class plgContentRichSnippetsVote extends JPlugin {

 function plgContentRichSnippetsVote(&$subject, $params) { 
	
	parent::__construct($subject, $params); 
    
	$mode = $this->params->def('mode', 1);
	
 }
 
 function rsvcode( $article, $params ) {
	
	global $mainframe;
	$lang = JFactory::getLanguage();
	$lang->load('plg_content_richsnippetsvote', JPATH_ADMINISTRATOR);  
		
		$rating_sum=0;
		$rating_cont=0;
		$db	= JFactory::getDBO();
		try
		{ 		
			$query='SELECT * FROM #__content_rating WHERE content_id='. $article->id;
			$db->setQuery($query);
			$votes=$db->loadObject();
		} 
		catch (RuntimeException $e)
		{ 
			$this->setError($e->getMessage());
		} 
    if (!$votes){
      try
      { 
		$query='SELECT * FROM #__content_extravote WHERE content_id='. $article->id;
		$db->setQuery($query);
        $votes=$db->loadObject();
      } 
      catch (RuntimeException $e)
      { 
        $this->setError($e->getMessage());
      } 
    }

    if (!$votes){
      try
      { 		
		$query='SELECT * FROM #__k2_rating WHERE itemID='. $article->id;
		$db->setQuery($query);
        $votes=$db->loadObject();
      } 
      catch (RuntimeException $e)
      { 
        $this->setError($e->getMessage());
      } 
    }

	if($votes) {

		$rating_sum = intval($votes->rating_sum);
		$rating_count = intval($votes->rating_count);
		$evaluate = ($rating_count==0) ? "0" : number_format($rating_sum/$rating_count,1);
		$counttype = ($this->params->get('counttype')) ? "reviewCount" : "ratingCount";
		$itemtype = "http://schema.org/Article";
		
		if ($this->params->get('counttype')){
		
			if ($rating_count==1)
				$counttype_text=JText::_('RSV_RATING');
			else	
				$counttype_text=JText::_('RSV_RATINGS');

		}
		else{
		
			if ($rating_count==1)
				$counttype_text=JText::_('RSV_VOTE');
			else	
				$counttype_text=JText::_('RSV_VOTES');

		}
		
		$microdata="<div class='richsnippetsvote'>
						<div itemscope itemtype='".$itemtype."'>
							<span itemprop='name'>".$article->title."</span> - <span itemprop='aggregateRating' itemscope itemtype='http://schema.org/AggregateRating'><span itemprop='ratingValue'>".$evaluate."</span> ".JText::_('RSV_OUTOF')." 
								<span itemprop='bestRating'>5</span>
							".JText::_('RSV_BASEDON')." 
								<span itemprop='".$counttype."'>".$rating_count."</span> ".$counttype_text."</span> 
						</div>
					</div>";
					
		if ($this->params->get('showtousers') == 0){
			$microdata.= '<style>
							.richsnippetsvote{
								display:none;
							}
						</style>';
		}			
					
		if (method_exists($article->params, 'get')){
			if (($article->params->get('show_vote')) OR ($article->params->get('itemRating')))			
				return $microdata;
		}			
	}

 }
 
  function onContentBeforeDisplay( $context, &$article, &$params ) {
	$app = JFactory::getApplication();
	if( $app->isAdmin() ){
		return;
	}
	
	if ($this->params->get('rsvposition')==1){
		return $this->rsvcode($article, $params);
	}
 
 }
 
 function onContentAfterDisplay( $context, &$article, &$params ) {
	$app = JFactory::getApplication();
	if( $app->isAdmin() ){
		return;
	}
	
	if ($this->params->get('rsvposition')==2){
		return $this->rsvcode($article, $params);
	}
 
 }
 
}
