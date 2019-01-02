<?php
/**
* TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.0
* @package TorTags
* @author Tormix Team
* @Copyright Copyright (C) Tormix, www.tormix.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
//for j3.0
if (TT_JVER==3)
{
	class JView extends JViewLegacy
	{};
}
class TorTagsViewDefault extends JView
{
	function display($tpl = null) 
	{
		$this->tags = $this->get('Items');
		$this->state = $this->get('State');
		$this->pagination = $this->get('Pagination');
		$this->user = &JFactory::getUser();

		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		parent::display($tpl);
	}

}
