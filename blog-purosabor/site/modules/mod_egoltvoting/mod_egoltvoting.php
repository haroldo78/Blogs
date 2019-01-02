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

// Set Lanuage
$language = JFactory::getLanguage();
$lang->load('plg_content_egoltvoting', JPATH_ADMINISTRATOR);

// Set Helper
require_once (dirname(__FILE__).'/helper.php');
$helper	= new modEgoltLikeHelper($params);
$items	= $helper->getList();

// Module Params
$modsfx		= $params->get('moduleclass_sfx');
$template	= $params->get('template', 'default');

// Component Params
// $cmparams	= $helper->getCMParams();

// Load Style-sheets
$document = JFactory::getDocument();
$csspath	= 'media/mod_egoltvoting/css/';
$lang 		= JFactory::getLanguage();
$isRTL		= $lang->isRTL();
		
JHTML::_('stylesheet', $csspath . 'mod_egoltvoting.css');
// if($isRTL) 
// {
	// JHTML::_('stylesheet', $csspath . 'mod_egoltvoting.rtl.css');	
// }

$modid = $module->id;

// Set Layout
require (JModuleHelper::getLayoutPath('mod_egoltvoting', $template.'/default'));
