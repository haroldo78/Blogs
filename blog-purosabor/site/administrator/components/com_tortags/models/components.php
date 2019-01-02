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

class TorTagsModelComponents extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'`c`.`id`','`c`.`component`'
			);
		}
		parent::__construct($config);
	}
	
	protected function populateState()
	{
		$this->setState('filter.search', $this->getUserStateFromRequest('com_tortags.filter.search', 'filter_search'));
		parent::populateState();
	}

	protected function getListQuery() 
	{
		$db = $this->_db;
		if (!$db) $db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('c.*');
		$query->from('`#__tortags_components` AS `c`');
		
		if (TT_JVER!=3) { 
			$orderCol	= $db->getEscaped($this->getState('list.ordering', '`c`.`component`'));
			$orderDirn	= $db->getEscaped($this->getState('list.direction', 'ASC'));
		}else
		{
			$orderCol	= $this->state->get('list.ordering', '`c`.`component`');
			$orderDirn	= $this->state->get('list.direction', 'asc');
		}
		$query->order($orderCol.' '.$orderDirn);

		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (TT_JVER!=3) {$search = $db->Quote('%'.$db->getEscaped($search, true).'%');}
			else			{$search = $db->Quote('%'.$db->escape($search, true).'%');}
			$query->where('`c`.`component` LIKE '.$search);
		}
		
		return $query;
	}
}
