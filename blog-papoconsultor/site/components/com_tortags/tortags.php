<?php
/**
* TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.0
* @package TorTags
* @author Tormix Team
* @Copyright Copyright (C) Tormix, www.tormix.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

//if (!JFactory::getUser()->authorise('core.manage', 'com_tortags')) {
//	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
//}

JLoader::register('TorTagsHelper', dirname(__FILE__).DS.'helpers'.DS.'helper.php');

//for j3.0
if (!defined('DS')) define('DS',DIRECTORY_SEPARATOR);

$version = new JVersion;
$ver = $version->getShortVersion();
if (!defined('TT_JVER')) define('TT_JVER',(int) substr($ver,0,strpos($ver,'.')));

//for j3.0
if (class_exists('JControllerLegacy'))
{
	$controller = JControllerLegacy::getInstance('TorTags');
}else
{
//j1.6+
	jimport('joomla.application.component.controller');
	$controller = JController::getInstance('TorTags');	
}
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();