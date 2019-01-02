<?php
/**
* TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.0
* @package TorTags
* @author Tormix Team
* @Copyright Copyright (C) Tormix, www.tormix.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
 
class com_tortagsInstallerScript
{
	private $version = '1.3.6';
	
	function install($parent) 
	{
		?>
		<div>
			<div class="tortags-info-left">
				<img src="<?php echo JURI::root();?>media/com_tortags/images/comp_logo.png" />
			</div>
			<div  class="tortags-info-left tortags-info-header" >
	        	<?php echo JText::_('COM_TORTAGS_INSTALL_MESSAGE');?>
	        </div>
	        <div  class="tortags-info-right">
	        	<img src="<?php echo JURI::root();?>media/com_tortags/images/tormix-16.png" />
	        		<?php echo JText::_('COM_TORTAGS_INSTALL_VERSION');?> <b><?php echo $this->version;?></b>        
	        </div>
        </div>
		<br/>
			<?php	
	}
 
	function uninstall($parent) 
	{
	}

	function update($parent) 
	{?>
		<div>
		<div class="tortags-info-left">
			<img src="<?php echo JURI::root();?>media/com_tortags/images/comp_logo.png" />
		</div>
		<div  class="tortags-info-left tortags-info-header">
      	  <?php echo JText::_('COM_TORTAGS_UPDATE_MESSAGE');?>
        </div>
        <div  class="tortags-info-right">
        <img src="<?php echo JURI::root();?>media/com_tortags/images/tormix-16.png" />
        	<?php echo JText::_('COM_TORTAGS_INSTALL_VERSION');?> <b><?php echo $this->version;?></b>        
        </div>
        </div>
		<br/>
	<?php	
	}
 
	function preflight($type, $parent) 
	{
		?>
		<style type="text/css">
			.tortags-info, .tortags-info-right, .tortags-info-left {
			    font-size: 12px;
			    padding: 2px;
			}
			
			.tortags-info-changelog
			{
				 border-radius: 4px 4px 4px 4px;
				 border: 1px solid #DDDDDD;
				 font-size:11px;
			} 
			.tortags-info-header
			{
				font-size:2em; 
				color:#55AA55;
			}
			
			.tortags-info-left
			{
				float:left;
			}
			.tortags-info-right
			{
				float:right;
			}
			.tortags-link-block
			{
				clear: both;
    			display: inline-block;
    			padding:3px;
    			width:100%;
			}
		</style>
		<?php
	}

	function postflight($type, $parent) 
	{
	//for j3.0
	if (!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
	
		$db	=& JFactory::getDBO();
		
		$columns_tags =(array) $db->setQuery("SHOW COLUMNS FROM #__tortags_tags")->loadColumn();
		$columns_comp =(array) $db->setQuery("SHOW COLUMNS FROM #__tortags_components")->loadColumn();
		
		/*VER 1.3 (from 3.0.0)*/
			if (!in_array('published', $columns_tags))
			{
			$db->setQuery("ALTER TABLE `#__tortags_tags` ADD `published` TINYINT UNSIGNED NOT NULL COMMENT 'Is tag published?'");
			$db->query();
			}
			
			if (!in_array('be_key', $columns_comp))
			{
			$db->setQuery("ALTER TABLE `#__tortags_components` ADD `be_key` VARCHAR( 60 ) NOT NULL COMMENT 'Admin request key id',ADD `fe_key` VARCHAR( 60 ) NOT NULL COMMENT 'Front request key id',ADD `where_enable` TINYINT NOT NULL COMMENT 'Where enable (0-all,1-only BE,2-only FE)'");
			$db->query();
			}
			
			if (!in_array('state_field', $columns_comp))
			{
			$db->setQuery("ALTER TABLE `#__tortags_components` ADD `state_field` VARCHAR( 250 ) NOT NULL COMMENT 'Publish state field',ADD `state_status` VARCHAR( 25 ) NOT NULL COMMENT 'Publish state value'");
			$db->query();
			}
			
		/*VER 1.2.0 (from 2.5.0) */
			if (!in_array('hits', $columns_tags))
			{
			$db->setQuery("ALTER TABLE `#__tortags_tags` ADD `hits` INT UNSIGNED NOT NULL COMMENT 'Hits' ");
			$db->query();
			}
			
			if (!in_array('id_field', $columns_comp))
			{
			$db->setQuery("ALTER TABLE `#__tortags_components` ADD `id_field` VARCHAR( 200 ) NOT NULL DEFAULT 'id' COMMENT 'component id field' AFTER `table` ");
			$db->query();
			}
			
			if (!in_array('exc_views', $columns_comp))
			{
			$db->setQuery("ALTER TABLE `#__tortags_components` ADD `exc_views` TEXT NOT NULL COMMENT 'Exceptions of views' ");
			$db->query();
			}
			
			$db->setQuery("SELECT `id` FROM `#__tortags_components` WHERE `component`='com_content' ");
			$id = $db->loadResult();
			if (!$id)
			{
				$db->setQuery("INSERT INTO `#__tortags_components` (`id`, `component`, `table`, `title_field`, `description_field`, `created_field`, `url_template`) VALUES
								(NULL, 'com_content', '#__content', 'title', 'introtext', 'created', 'index.php?option=[COMPONENT]&view=article&id=[ID]')");
				$db->query();
			}
			$db->setQuery("SELECT `id` FROM `#__tortags_components` WHERE `component`='com_categories' ");
			$id = $db->loadResult();
			if (!$id)
			{
				$db->setQuery("INSERT INTO `#__tortags_components` (`id`, `component`, `table`, `title_field`, `description_field`, `created_field`, `url_template`) VALUES
								(NULL, 'com_categories', '#__categories', 'title', 'description', 'created_time', 'index.php?option=com_content&view=category&layout=blog&id=[ID]')");
				$db->query();
			}

		/**/
	$version = new JVersion;
	$ver = $version->getShortVersion();
	$ver =(int) substr($ver,0,strpos($ver,'.'));
	
	?>
	<br />
	<div style="padding:14px;">
		<div class="tortags-info-left">
			<?php echo JText::_('COM_TORTAGS_DESCRIPTION');?>
		</div>
		<br />
		<div class="tortags-link-block">
		<div class="panel">
			<div id="cpanel">
				<div class="<?php if ($ver!=3) echo 'icon-wrapper'; else echo 'nav-header';?>">
					<div class="icon">
						<a href="<?php echo JURI::root();?>administrator/index.php?option=com_tortags&view=components">
							<img alt="" src="<?php echo JURI::root();?>administrator/components/com_tortags/assets/images/components48.png">
							<span><?php echo JText::_('COM_TORTAGS_MENU_COMOPONENTS');?></span></a>
					</div>
				</div>
				<div class="<?php if ($ver!=3) echo 'icon-wrapper'; else echo 'nav-header';?>">
					<div class="icon">
						<a href="<?php echo JURI::root();?>administrator/index.php?option=com_tortags&view=tags">
							<img src="<?php echo JURI::root();?>administrator/components/com_tortags/assets/images/tags48.png" />
							<span><?php echo JText::_('COM_TORTAGS_MENU_TAGS');?></span></a>
					</div>
				</div>
				<div class="<?php if ($ver!=3) echo 'icon-wrapper'; else echo 'nav-header';?>">
					<div class="icon">
						<a href="<?php echo JURI::root();?>administrator/index.php?option=com_tortags">
							<img src="<?php echo JURI::root();?>administrator/components/com_tortags/assets/images/about48.png" />
							<span><?php echo JText::_('COM_TORTAGS_MENU_ABOUT');?></span></a>
					</div>
				</div>
			</div>
		</div>
		</div>
		<div  class="tortags-info tortags-info-changelog">
		<?php
        	jimport( 'joomla.filesystem.file' );
			$file		= JPATH_ROOT . DS . 'administrator' . DS .'components' . DS . 'com_tortags' . DS . 'changelog.txt';
			if (file_exists($file))
			{
				$content	= JFile::read( $file );
				echo '<pre>'.$content.'</pre>';	
			}else echo $file;
		?>
		</div>
	</div>
	<?php
	}
}
