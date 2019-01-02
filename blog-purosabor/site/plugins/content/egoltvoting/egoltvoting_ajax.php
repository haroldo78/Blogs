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

// Set flag that this is a parent file
define('_JEXEC', 1);

// Check Joomla! Library and direct access
defined('_JEXEC') or die('Direct access denied!');	

define( 'DS', DIRECTORY_SEPARATOR );

define('JPATH_BASE', dirname(__FILE__).DS.'..'.DS.'..'.DS.'..' );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

jimport('joomla.database.database');
jimport('joomla.database.table');

$app = JFactory::getApplication('site');
$app->initialise();

// Load languages
$lang = JFactory::getLanguage();
$lang->load('plg_content_egoltvoting', JPATH_ADMINISTRATOR);

$user = JFactory::getUser();

$plugin	= JPluginHelper::getPlugin('content', 'egoltvoting');

$params = new JRegistry;
$params->loadString($plugin->params);

$tflag	= TRUE;
$status	= 'fail';
$input	= JFactory::getApplication()->input;
$id		= $input->get('cid',null,'INT');
$lval	= $input->get('lval',null,'INT');
$sr		= $input->get('sr',null,'INT');
$db		= JFactory::getDbo();
$currip = $_SERVER['REMOTE_ADDR'];

if($params->get('regonly', 0))
{
	if ($user->guest)
	{
		$status	= 'noaccess';
		$tflag = FALSE;
	}
}

// Validate rate value
$lval_right = array(1,2,3,4,5);
if(!in_array($lval,$lval_right))
	$tflag = FALSE;
	
// Only Joomla! content
if($sr !='1')
	$tflag = FALSE;

// Categories and Articles filter
if($params->get('encats') or $params->get('discats') or $params->get('disarts'))
{
	if( $params->get('disarts') and (in_array($id, $params->get('disarts'))) )
	{
			echo 'noaccess';
			exit();	
	}
		
	if($params->get('discats') or $params->get('encats'))
	{
		// get article category
		$query = $db->getQuery(true);
		$query->select('id, catid');
		$query->from('#__content');
		$query->where('id = '.$db->quote($id));
		$db->setQuery( (string)$query );
		$cnres = $db->loadObject();
		$catid = $cnres->catid;
		
		if( $params->get('discats') and (in_array($catid, $params->get('discats'))) )
		{
			echo 'noaccess';	
			exit();
		}
		
		if( $params->get('encats') and (!in_array($catid, $params->get('encats'))) )
		{
			echo 'noaccess';
			exit();			
		}
		
	}
}

if($tflag)
{
	$va = $params->get('voteagain', -1);
	
	$query = $db->getQuery(true);
	$query->select('*');
	$query->from('#__content_rating');
	$query->where('content_id = '.$db->quote($id));
	$db->setQuery( (string)$query );
	$jres = $db->loadObject();
	
	$query = $db->getQuery(true);
	$query->select('*');
	$query->from('#__egoltvoting');
	$query->where('cid = '.$db->quote($id));
	$query->where('service = '.$db->quote(1));
	$db->setQuery( (string)$query );
	$res = $db->loadObject();
			
	// check for empty row	
	if( (!empty($res)) and (!empty($jres)) )
	{
		$upflag = 0;
		if($currip != $jres->lastip)
		{
			$upflag = 1;
		}
		elseif($va == '1')
		{
			$upflag = 1;
		}
		elseif($va != '-1')
		{
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__egoltvoting');
			$query->where('cid = '.$db->quote($id));
			$query->where('lastip = '.$db->quote($currip));
			$query->where('service = '.$db->quote(1));
			$query->where('lastdate <> '. $db->Quote($db->getNullDate()));
			switch($va) 
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
								
				case '1year':
						$dur_param = 'INTERVAL 1 YEAR';
				break;
			}
			$query->where('lastdate BETWEEN DATE_SUB(NOW(), '. $dur_param .') AND NOW()');
			$db->setQuery( (string)$query );
			// die($query);
			$res2 = $db->loadObject();
				
			if(empty($res2))
			{
				$upflag = 1;
			}
		}
			
		// Update
		if($upflag == 1)
		{
			$counter = $jres->rating_count+1;
			$sum = $jres->rating_sum+$lval;
			
			// General Table
			$query	= $db->getQuery(true);
			$query->update('`#__egoltvoting`');
			$query->set('lastip = '.$db->quote($currip));
			$query->set('counter = '.$db->quote($counter));
			$query->set('sum = '.$db->quote($sum));
			$query->set('lastdate = NOW()');
			$query->where('cid = '.$db->quote($id));
			$query->where('service = '.$db->quote(1));
			$db->setQuery( (string)$query );	
			if (!$db->execute()) {
				return false;
			}
			
			// Joomla! Table
			$query	= $db->getQuery(true);
			$query->update('`#__content_rating`');
			$query->set('lastip = '.$db->quote($currip));
			$query->set('rating_count = '.$db->quote($counter));
			$query->set('rating_sum = '.$db->quote($sum));
			$query->where('content_id = '.$db->quote($id));
			$db->setQuery( (string)$query );	
			if (!$db->execute()) {
				return false;
			}
			
			$status = 'thanks';		
		}
		else
		{
			$status = 'liked';
		}	
	}
	elseif(empty($res) and empty($jres))
	{
		// General Table
		$query = $db->getQuery(true);
		$query->insert('#__egoltvoting');
		$query->set('cid = '.$db->quote($id));
		$query->set('service = '.$db->quote(1));
		$query->set('lastip = '.$db->quote($currip));
		$query->set('lastdate = NOW()');
		$query->set('counter = '.$db->quote(1));
		$query->set('sum = '.$db->quote($lval));
		$db->setQuery( (string)$query );	
		if (!$db->execute()) {
			return false;
		}
		
		// Joomla! Table
		$query	= $db->getQuery(true);
		$query->insert('`#__content_rating`');
		$query->set('lastip = '.$db->quote($currip));
		$query->set('rating_count = '.$db->quote(1));
		$query->set('rating_sum = '.$db->quote($lval));
		$query->set('content_id = '.$db->quote($id));
		$db->setQuery( (string)$query );	
		if (!$db->execute()) {
			return false;
		}		
		
		$status = 'thanks';
	}
	elseif(empty($res))
	{
			$counter = $jres->rating_count+1;
			$sum = $jres->rating_sum+$lval;
			
			// General Table
			$query	= $db->getQuery(true);
			$query->insert('`#__egoltvoting`');
			$query->set('lastip = '.$db->quote($currip));
			$query->set('counter = '.$db->quote($counter));
			$query->set('sum = '.$db->quote($sum));
			$query->set('lastdate = NOW()');
			$query->where('cid = '.$db->quote($id));
			$query->where('service = '.$db->quote(1));
			$db->setQuery( (string)$query );	
			if (!$db->execute()) {
				return false;
			}
			
			// Joomla! Table
			$query	= $db->getQuery(true);
			$query->update('`#__content_rating`');
			$query->set('lastip = '.$db->quote($currip));
			$query->set('rating_count = '.$db->quote($counter));
			$query->set('rating_sum = '.$db->quote($sum));
			$query->where('content_id = '.$db->quote($id));
			$db->setQuery( (string)$query );	
			if (!$db->execute()) {
				return false;
			}
			
			$status = 'thanks';			
	}
	
	// Submit Logs
	if($status == 'thanks')
	{
		$query = $db->getQuery(true);
		$query->insert('#__egoltvoting_logs');
		$query->set('service = '.$db->quote(1));
		$query->set('cid = '.$db->quote($id));
		$query->set('logip = '.$db->quote($currip));
		$query->set('logdate = NOW()');
		$query->set('rate = '.$db->quote($lval));
		$db->setQuery( (string)$query );	
		if (!$db->execute()) {
			return false;
		}			
	}
}

echo $status;
