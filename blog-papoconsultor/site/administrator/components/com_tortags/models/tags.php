<?php
/**
* TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.0
* @package TorTags
* @author Tormix Team
* @Copyright Copyright (C) Tormix, www.tormix.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class TorTagsModelTags extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'`t`.`id`','`t`.`title`', '`comp_count`','`t`.`published`'
			);
		}
		parent::__construct($config);
	}
	
	protected function populateState()
	{
		$this->setState('filter.search_tag', $this->getUserStateFromRequest('com_tortags.filter.search_tag', 'filter_search_tag'));
		parent::populateState();
	}

	protected function getListQuery() 
	{
		$db = $this->_db;
		$query = $db->getQuery(true);

		$query->select('t.*');
		$query->select('(SELECT COUNT(`g`.`oid`) FROM `#__tortags` AS `g` WHERE `g`.`tid`=`t`.`id`) AS `comp_count`');
		$query->from('`#__tortags_tags` AS `t`');
		
		if (TT_JVER!=3) { 
			$orderCol	= $db->getEscaped($this->getState('list.ordering', '`t`.`title`'));
			$orderDirn	= $db->getEscaped($this->getState('list.direction', 'ASC'));
		}else
		{
			$orderCol	= $this->state->get('list.ordering', '`t`.`title`');
			$orderDirn	= $this->state->get('list.direction', 'asc');
		}
		$query->order($orderCol.' '.$orderDirn);
		
		$search = $this->getState('filter.search_tag');
		if (!empty($search)) {
			if (TT_JVER!=3) {$search = $db->Quote('%'.$db->getEscaped($search, true).'%');}
			else			{$search = $db->Quote('%'.$db->escape($search, true).'%');}
			$query->where('`t`.`title` LIKE '.$search);
		}
		return $query;
	}
}
