<?php
/**
* TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.0
* @package TorTags
* @author Tormix Team
* @Copyright Copyright (C) Tormix, www.tormix.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
// no direct access
defined('_JEXEC') or die;

// Include the weblinks functions only once
require_once dirname(__FILE__).'/helper.php';
JFactory::getDocument()->addStyleSheet(JURI::root().'modules/mod_tortags_often_clouds/tmpl/style.css');

$list = modTorTagsOftenCloudsHelper::getList($params);

if (!count($list)) {
	return;
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_tortags_often_clouds',$params->get('layout', 'default'));
