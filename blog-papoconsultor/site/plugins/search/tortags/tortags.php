<?php
/**
* TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.0
* @package TorTags
* @author Tormix Team
* @Copyright Copyright (C) Tormix, www.tormix.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die();
jimport( 'joomla.html.parameter' );
//for j3.0
if (!defined('DS')) define('DS',DIRECTORY_SEPARATOR);

class plgSearchTorTags extends JPlugin
{
   
   public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	function onContentSearchAreas() {
		static $areas = array(
			'tortags' => 'PLG_SEARCH_TORTAGS'
			);
			return $areas;
	}

	function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
		$text = trim($text);
		if ($text == '') {return array();}
		$return = array();
		
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$limit	= $this->params->def('search_limit', 50);
		$nav	= $this->params->def('nav', 2);
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		
		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}
		
		$db->setQuery('UPDATE `#__tortags_tags` SET `hits`=hits+1 WHERE `title`='.$db->quote($text));
		$db->query();
			
		$query = $db->getQuery(true);
		$query->select('t.id');
		$query->from('#__tortags_tags AS t');
		$query->where('(`t`.`published`=1)');				
		switch ($phrase)
		{
			case 'exact':
				$text		= $db->Quote('%'.$db->escape($text, true).'%', false);
				$query->where("`t`.`title` LIKE ".$text);
				break;

			case 'all':
			case 'any':
			default:
				$words	= explode(' ', $text);
				$wheres = array();
				foreach ($words as $word)
				{
					$word		= $db->Quote('%'.$db->escape($word, true).'%', false);
					$wheres2	= array();
					$wheres2[]	= '`t`.`title` LIKE '.$word;
					$wheres[]	= implode(' OR ', $wheres2);
				}
				$where	= '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				$query->where($where);
				break;
		}
				
		$db->setQuery($query);
		$tagids = $db->loadColumn();
		
		if(!sizeof($tagids)) return array();
		
		$ids = implode(',',$tagids);
		
		$query = $db->getQuery(true);
		$query->select('m.oid');
		$query->from('#__tortags AS m');
		$query->where('m.tid IN ('.$ids.')');
		$query->group('m.oid');
		$db->setQuery($query);
		$oids = $db->loadColumn();
		if(!sizeof($oids)) return array();

		foreach ( $oids as $oid ) 
		{
			$query = $db->getQuery(true);
			$query->select('m.item_id');
			$query->from('#__tortags AS m');
			$query->where('m.tid IN ('.$ids.')');
			$query->where('m.oid='.$oid);
			$db->setQuery($query);
			$itemids = $db->loadColumn();
			if (sizeof($itemids))
			{
				$query = $db->getQuery(true);
				$query->select('c.*');
				$query->from('#__tortags_components AS c');
				$query->where('c.id ='.$oid);
				$db->setQuery($query);
				$param = $db->loadObject();
				if ($param->table)
				{
					if ($param->table!='#__')
					{
						$query = $db->getQuery(true);
						$table 			= $param->table;
						$title			= $param->title_field?$param->title_field:'""';
						$id_field		= $param->id_field?$param->id_field:'id';
						$description	= $param->description_field?$param->description_field:'""';
						$created		= $param->created_field?('`t`.`'.$param->created_field.'`'):'""';
						$query->select($id_field.' AS `id`');
						$query->select($title.' AS `title`');
						$query->select($description.' AS `text`');
						$query->select($created.' AS `created`');
						$query->select('"1" AS browsernav');
						if (isset($param->state_field) && isset($param->state_status))
						{
							if ($param->state_field && $param->state_status)
							{
								$query->where('`t`.`'.$param->state_field.'`='.$param->state_status);
							}
						}						
						if ($param->component=='com_content') 
						 {
						 	$query->select('`catid`');
						 	$query->select('`alias`');
						 	if ($groups) $query->where('`access` IN ('.$groups.')');
							$query->select('`alias`');
						 }else
						 if ($param->component=='com_marketplace') 
						 {
						 	$query->select('`category_id` AS `catid`');						
						 }else
						  if ($param->component=='com_jshopping') 
						 {
						 	$query->select('(SELECT `jpc`.`category_id` FROM `#__jshopping_products_to_categories` AS `jpc` WHERE `jpc`.`product_id`=`t`.`product_id` LIMIT 1) AS `catid`');
						 }else
						 if ($param->component=='com_virtuemart') 
						 {
							$query->where('(SELECT `vmt`.`published` FROM `#__virtuemart_products` AS `vmt` WHERE `vmt`.`virtuemart_product_id`=`t`.`virtuemart_product_id` LIMIT 1)=1');
							$query->select('(SELECT `vmtm`.`created_on` FROM `#__virtuemart_products` AS `vmtm` WHERE `vmtm`.`virtuemart_product_id`=`t`.`virtuemart_product_id` LIMIT 1) AS `created`');
						 }
						 else
						 if ($param->component=='com_form2content') 
						 {
							$query->select('`reference_id` AS `id`');
							$query->select('`catid`');
						 	$query->select('`alias`');
						 }
						 else
						 if ($param->component=='com_sobipro') 
						 {
							$query->leftjoin('`#__sobipro_field_data` AS `sbpd` ON `sbpd`.`sid`=`t`.`id` AND `sbpd`.`fid`=1');
							$query->select('`sbpd`.`baseData` AS `title`');
							$query->select('`parent` AS `pid`');
							$created='created';
						 }
						 else 	$query->select('"" AS `catid`');
						 
						 switch ($ordering) {
							case 'oldest':
								$query->order($created.' ASC');
								break;
							case 'popular':
								$query->order($created.' DESC');
								break;
							case 'alpha':
								$query->order($title.' ASC');
								break;
							case 'newest':
							default:
								$query->order($created.' DESC');
								break;
						}
						
						$query->where($id_field.' IN ('.implode(',',$itemids).')');
						$query->from($table.' AS `t`');
						$db->setQuery($query);
						
						$res = $db->loadObjectList();

						if (sizeof($res))
						{
							foreach ( $res as $r ) 
							{
								$a = $b = array();
								$a[] = '[COMPONENT]';
								$b[] = $param->component;
								$a[] = '[ID]';
								$b[] = $r->id;
								
								$url= str_replace($a,$b,$param->url_template);
								$menu = &JSite::getMenu();
								$item = $menu->getItems('link', $url, true);
								
								switch ( $param->component ) 
								{
									case 'com_sobipro':	
										jimport( 'joomla.filter.output' );
										$alias = JFilterOutput::stringURLSafe($r->title);
										$url.= '&pid='.$r->pid;
										$url = str_replace($r->id,$r->id.':'.$alias,$url);
									break;
								}
								
								$Itemid = JRequest::getInt('Itemid');
								if(isset($item)) 
								if(is_object($item)) $Itemid = $item->id;
								
							    if (isset($Itemid)) $url.='&Itemid='.$Itemid;
								
								switch ( $param->component ) 
								{
									case 'com_content':	
									case 'com_form2content':	
										require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
										$r->slug = $r->alias ? ($r->id . ':' . $r->alias) : $r->id;
										$r->href = ContentHelperRoute::getArticleRoute($r->slug?$r->slug:$r->id,$r->catid);
									break;
									
									case 'com_category':	
										require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
										$r->href = ContentHelperRoute::getCategoryRoute($r->id);
									break;
									default:
										$r->href = JRoute::_($url);
									break;
								}
								$r->section='';
								$r->browsernav=$nav;
								$return[]=$r;
							}
						}
					}
				}
			}
			
		}
		//echo '<pre>';print_R($return);echo'</pre>';
		return $return;
	}
}
?>