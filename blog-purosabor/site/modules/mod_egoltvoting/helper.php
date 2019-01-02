<?php
/**
 * @package   	Egolt Voting
 * @link 		http://www.egolt.com
 * @copyright 	Copyright (C) Egolt - www.egolt.com
 * @author    	Soheil Novinfard
 * @license    	GNU/GPL 2
 *
 * Name:		Egolt Voting
 * License:		GNU/GPL 2
 * Product:		http://www.egolt.com/products/egoltvoting
 */

// Check Joomla! Library and direct access
defined('_JEXEC') or die('Direct access denied!');

// Check Egolt Framework
// defined('_EGOINC') or die('Egolt Framework not installed!');

require_once(JPATH_SITE.'/components/com_content/helpers/route.php');

class modEgoltLikeHelper
{
	var $_params;

	function __construct($params)
	{		
		$this->_params	= $params;
	}

    public function getList()
    {		
		$qt		= $this->_params->get('egqt');
		$votedur = $this->_params->get('votedur', -1);
		$leastvote = $this->_params->get('leastvote', 0);
		$db		= JFactory::getDBO();

		// Get Ratings
		// wihtout duration
		if($votedur == '-1')
		{
			$query = $db->getQuery(true);
			$query->select('*, rating_sum/rating_count as average');
			$query->from('#__content_rating');
			if($leastvote)
				$query->where('rating_count >= '.$db->quote($leastvote));
			$query->order('average DESC');	
			$db->setQuery( (string)$query, 0, $qt);
			$items = $db->loadObjectList();
		}
		// rating with duration
		else
		{
			$query = $db->getQuery(true);
			$query->select('content_id');
			$query->from('#__content_rating');
			$db->setQuery($query);
			$all_items = $db->loadObjectList();
			
			$items = array();
			$curcount = 0;
			foreach($all_items as $item)
			{
				if($curcount < $qt)
				{
					$query = $db->getQuery(true);
					$query->select('SUM(rate) as rating_sum');
					$query->select('COUNT(*) as rating_count');
					$query->from('#__egoltvoting_logs');
					$query->where('cid = '.$db->quote($item->content_id));
					$query->where('service = '.$db->quote(1));
					switch($votedur) 
					{
						case '1day':
							$dur_param = 'INTERVAL 1 DAY';
						break;
								
						case '1week':
							$dur_param = 'INTERVAL 7 DAY';
						break;
								
						case '1month':
							$dur_param = 'INTERVAL 1 MONTH';
						break;
								
						case '3month':
							$dur_param = 'INTERVAL 3 MONTH';
						break;
								
						case '6month':
							$dur_param = 'INTERVAL 6 MONTH';
						break;
									
						case '9month':
							$dur_param = 'INTERVAL 9 MONTH';
						break;
								
						case '1year':
							$dur_param = 'INTERVAL 1 YEAR';
						break;
					}
					$query->where( 'logdate BETWEEN DATE_SUB(NOW(), '. $dur_param .') AND NOW()');						
						
					$db->setQuery($query);
					$durres = $db->loadObject();
					
					if($durres->rating_count)
					{
						if($leastvote and ($durres->rating_count < $leastvote))
							break;
						
						++$curcount;
						$items[$curcount] = new stdClass();
						$items[$curcount]->content_id = $item->content_id;
						$items[$curcount]->rating_sum = $durres->rating_sum;
						$items[$curcount]->rating_count = $durres->rating_count;
						$items[$curcount]->average = $durres->rating_sum / $durres->rating_count;
					}
				}
				else{
					break;
				}
			}
			JArrayHelper::sortObjects($items, array('average','rating_count'), -1);
		}
		
		// process items
		foreach ($items as $i => $item) 
		{
			$cid = $item->content_id;
				
			$query	= $db->getQuery(true);
			$query->select('a.*');
			$query->from('#__content as a');
			$query->where('id = '.$db->quote($cid));
			$db->setQuery( (string)$query );
			$content = $db->loadObject();
			
			$item->percent = round($item->average*20);
			$item->title = $content->title;
			$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($cid, $content->catid));	
		}
		
		return $items;
    }

}
