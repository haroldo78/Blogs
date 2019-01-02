<?php
/**
* TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.0
* @package TorTags
* @author Tormix Team
* @Copyright Copyright (C) Tormix, www.tormix.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

class plgContentTorTags extends JPlugin {
	
	function __construct($subject, $params){
		parent::__construct($subject, $params);
	}

	public function onContentAfterSave($context, &$article, $isNew)
	{
		if (!$isNew) return true;
		
		$app = JFactory::getApplication();
		
		$tags = trim(htmlspecialchars(JRequest::getVar('ttnewtags')));
		$option	= JRequest::getVar('option');

		if (!$tags) return true;
		$tagarray = array();	
		$components 	= $this->getAlloweComponents();
		if (sizeof($components))
		if (in_array($option,$components))
		 {
		 	
		 	if (isset($article->introtext)) $article->introtext = str_replace('{tortags,[new_id]','{tortags,'.(int)$article->id,$article->introtext);
		 	if (isset($article->fulltext)) $article->fulltext =  str_replace('{tortags,[new_id]','{tortags,'.(int)$article->id,$article->fulltext);
		 	if (isset($article->text)) $article->text =  str_replace('{tortags,[new_id]','{tortags,'.(int)$article->id,$article->text);
		 	
		 	if ($option=="com_content")
		 	{
		 	$db = JFactory::getDBO();
		 	$query = $db->getQuery(true);
				$query->update('`#__content`');
				$query->where('`id`='.$article->id);
				$query->set('`introtext`='.$db->quote($article->introtext));
				$query->set('`fulltext`='.$db->quote($article->fulltext));
				$db->setQuery($query);
			$db->query();
		 	}
		 	
		 	$tagarray = explode(',',$tags);
		 	if (sizeof($tagarray))
		 	{
		 		$db = JFactory::getDBO();
		 		
		 		foreach ( $tagarray as $tag ) 
		 		{
		 			$tag = trim($tag);
		 			if (!$tag) continue;
					$query = $db->getQuery(true);
					$query->select('`id`');
					$query->from('`#__tortags_tags`');
					$query->where('`title`='.$db->quote($tag));
					$db->setQuery($query);
					$tid = $db->loadResult();
			
					$query = $db->getQuery(true);
					$query->select('`id`');
					$query->from('`#__tortags_components`');
					$query->where('`component`='.$db->quote($option));
					$db->setQuery($query);
					$oid = $db->loadResult();
			
					if (!$tid)
					{
						$object = new stdclass();
						$object->title = $tag;
						$object->published = 1;
						$db->insertObject('#__tortags_tags', $object);
						$tid = (int)$db->insertid();	
					}
			
					$object = new stdclass();
					$object->tid = $tid;
					$object->oid = $oid;
					$object->item_id = $article->id;
					$db->insertObject('#__tortags', $object); 
				}
		 	}
		 }
		 //die;
		return true;
	}
	 	function onContentPrepare( $context, &$article, &$params, $page = 0 )  
        {
        	if (strpos($article->text, 'tortags') === false) 
        	{
        		return true;
        	}
        	 $regex		= '/{tortags,(\d+),(\d+)}/i';
			 $matches	= array();
        	 preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);
        	 $oid = null;
        	 $option	= JRequest::getVar('option');

			$components 	= $this->getAlloweComponents();
        	 
        	if (sizeof($matches)) 
        	{
        		$style = 'default.css';	 	
				JFactory::getDocument()->addStylesheet(JURI::root().'components/com_tortags/assets/css/'.$style);
			
        		foreach ( $matches as $match ) {
       
	        	if (sizeof($match)==3)
	        	{  	$id		=(int)$match[1];
	        	   	$oid    = (int)$match[2];
	        	   	 $option = $this->getOptionByOid($oid);
	        	} else {return;}
        		if (!isset($id)) continue;
        			 
				 $view		= JRequest::getVar('view');
				 if ($option=="com_content" && $view=="category") $option="com_categories";
				 
				 $body 		= JResponse::getBody();
				 
				 $return = $this->getReturnTags($components, $id, $option, $oid);
			 	$regex = '/{tortags,'.$id.','.$oid.'}/i';
				$article->text = preg_replace($regex, $return, $article->text);
        	  }
        	}
		 return;
        }

 private function getReturnTags($components=null, $id=0, $option='', $oid=0)
    {
		$tags = $this->getTags($id, $option, $oid);		 
		 $return = '';
		if (sizeof($components))
		 if (in_array($option,$components))
		 {
		 	$app = JFactory::getApplication();
			$settings = &JComponentHelper::getParams('com_tortags');
			$style = $settings->get('style','default.css');	 	
			JFactory::getDocument()->addStylesheet(JURI::root().'components/com_tortags/assets/css/'.$style);
			
		 	$return	.= '<div id="tt-tags" class="tt-tags">';
		 	if (sizeof($tags))
			{
			$img 		= JURI::root().'components/com_tortags/assets/images/tags.png';
			 $img_but	= JURI::root().'components/com_tortags/assets/images/tt_button_left.png';
			 $img_blank	= JURI::root().'components/com_tortags/assets/images/tt_button_blank.png';
				
			 	foreach ( $tags as $tag ) 
			 	{	//$menu = &JSite::getMenu();
					$menu	= & JApplication::getMenu('site');
					$Itemid = (isset($menu->getActive()->id))?('&Itemid='.$menu->getActive()->id):'';
                    $link = JRoute::_('index.php?option=com_search&searchword='.trim(substr($tag->title, 0, 150)).'&areas[]=tortags'.$Itemid);

			 		$return .='<div id="tagid_'.$tag->id.'" class="tt_button">' .
			 				'<div class="tt_end">' .
			 					'<a href="'.$link.'" title="'.$tag->title.'">' .
			 						'<span class="tt-2gtr"><img src="'.$img.'"  alt="'.$tag->title.'" />' .
			 								''.$tag->title.'</span></a>' .
			 				'</div>' .
			 				'</div>';
			 	}
			 }
		 	$return	.= '</div>';
		 }		
		 return $return;
   }
   
   public function onContentBeforeDisplay($context, &$article, &$params, $limitstart=0)
	{
		$eai = $this->params->get('enbl_autoinsert', 1);
		if ($eai)
		{
			$sin = $this->params->get('showin', 0);
			$soiart = $this->params->get('showonlyinarticle', 0);
			$pretext = $this->params->get('pretext', '');
	         $option	= JRequest::getVar('option');
				 $components 	= $this->getAlloweComponents();
				 $return = $addtags = '';
			 if ($context=='com_content.article') $option='com_content';
			 
			 if ($option=="com_content" && isset($article->id))
			 {
			 	$oid = $this->getOidByOption($option);
			 	$tags = $this->getTags($article->id, $option, $oid);
			 	if (!sizeof($tags)) return;
			 	if ($pretext) $pretext = '<div class="tt_pretext">'.$pretext.'</div>';
			 	
	        		if ($oid)	 
	        		{
	        			$view = JRequest::getVar('view');
	        			if ($soiart && $view!='article') {$addtags =	'' ;} 
	        			else       			
	        			{
	        				$addtags = JHTML::_('content.prepare', '{tortags,'.$article->id.','.$oid.'}');
	        			}
	        		}
	        	        	
			 if ($sin==1)
			 {
			 	if (!isset($article->text))     
			 	if (isset($article->fulltext)) $article->fulltext = $pretext.$addtags.$article->fulltext;
			 	else $article->introtext = $pretext.$addtags.$article->introtext;
			 	if (isset($article->text)) $article->text = $pretext.$addtags.$article->text;
			 }
			 else
			 {
			 	if (!isset($article->text))
			 	if (isset($article->fulltext)) $article->fulltext .= $pretext.$addtags;
			 	else $article->introtext .= $pretext.$addtags;
			 	if (isset($article->text)) $article->text .= $pretext.$addtags;
			 }
	        }
		}
        return;
    }
	
	protected function getAlloweComponents()
	{
		$db = JFactory::getDBO();
			
			$query = $db->getQuery(true);
			$query->select('`component`');
			$query->from('`#__tortags_components`');
			$db->setQuery($query);
			$cmpts = $db->loadColumn();
		return $cmpts;	
	}
	
	protected function getOptionByOid($oid=0)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('`component`');
		$query->from('`#__tortags_components`');
		$query->where('`id`='.$db->quote($oid));
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	protected function getTags($id, $option, $oid=null)
	{
		$db = JFactory::getDBO();
		
		if (!$oid)
		{
		$query = $db->getQuery(true);
		$query->select('`id`');
		$query->from('`#__tortags_components`');
		$query->where('`component`='.$db->quote($option));
		$db->setQuery($query);
		$oid = $db->loadResult();
		}
			
		$query = $db->getQuery(true);
		$query->select('`t`.*');
		$query->from('`#__tortags_tags` AS `t`');
		$query->join('INNER','`#__tortags` AS `m` ON `m`.`tid`=`t`.`id`');
		$query->where('`m`.`item_id`='.(int)$id);
		$query->where('`m`.`oid`='.(int)$oid);
		$query->where('`t`.`published`=1');
		$query->order('`t`.`title` ASC');
		$db->setQuery($query);
		$tags = $db->loadObjectList();
		return $tags;
	}
	
	protected function getOidByOption($option='')
	{$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('`id`');
		$query->from('`#__tortags_components`');
		$query->where('`component`='.$db->quote($option));
		$db->setQuery($query);
		$oid = $db->loadResult();
		return $oid;
	}

}
?>