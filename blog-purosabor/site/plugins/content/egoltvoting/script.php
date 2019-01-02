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

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
 
class plgcontentegoltvotingInstallerScript
{
	function preflight( $type, $parent ) {
		if ( $type == 'update' ) {
			$jversion = new JVersion();
	 
			// get new version
			$newRelease = $parent->get( "manifest" )->version;
			
			// get old version
			$oldRelease = $this->getParam('version');
			
			// abort if the plugin being installed is not newer
			if ( version_compare( $newRelease, $oldRelease, 'gt' ) ) {
				if ( version_compare( $oldRelease, '2.0.0', 'lt' ) )
				{
					$db = JFactory::getDbo();
					
					$query =
					"CREATE TABLE IF NOT EXISTS `#__egoltvoting` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `service` int(3) NOT NULL,
					  `cid` int(11) NOT NULL,
					  `lastip` varchar(50) NOT NULL,
					  `lastdate` datetime NOT NULL,
					  `counter` int(10) NOT NULL DEFAULT '0',
					  `sum` int(10) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`),
					  KEY `egoltvoting_idx` (`cid`)
					);
					";
					$db->setQuery($query);
					$db->execute();	
					
					$query =
					"CREATE TABLE IF NOT EXISTS `#__egoltvoting_logs` (
					  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					  `service` int(3) NOT NULL,
					  `cid` int(11) NOT NULL,
					  `logip` varchar(50) NOT NULL,
					  `logdate` datetime NOT NULL,
					  `rate` int(2) NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `egoltvoting_idx` (`cid`)
					);
					";
					$db->setQuery($query);
					$db->execute();
					
					$query =
					"INSERT INTO `#__egoltvoting` (`service`, `cid`, `lastip`, `lastdate`, `sum`, `counter`) 
					SELECT 1, `content_id`, `lastip`, NOW(), `rating_sum`, `rating_count` from `#__content_rating`;
					";
					$db->setQuery($query);
					$db->execute();
					
					JFactory::getApplication()->enqueueMessage('Database Structures Updated!');
				}
			}
		}
	}
	
	// get a variable from the manifest file (actually, from the manifest cache)
	function getParam( $name ) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE element="egoltvoting" and folder="content" ');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[ $name ];
	}
	
}
