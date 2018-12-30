<?php
/**
* TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.0
* @package TorTags
* @author Tormix Team
* @Copyright Copyright (C) Tormix, www.tormix.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

//for j3.0
if (TT_JVER==3)
{
	class JController extends JControllerLegacy
	{};
}

class TorTagsController extends JController
{
	function display($cachable = false) 
	{
		$document = JFactory::getDocument();
		$document->addStyleSheet('components/com_tortags/assets/css/tortags.css');

		$viewName = JRequest::getCmd('view', 'about');
		$this->default_view = $viewName;

		TorTagsHelper::addSubmenu($viewName);

		parent::display($cachable);
	}
	
	public function history()
	{
		echo '<h2>'.JText::_('COM_TORTAGS_VERSION_HISTORY').'</h2><br/>';
		jimport ('joomla.filesystem.file');
		if (!JFile::exists(JPATH_COMPONENT_ADMINISTRATOR.'/changelog.txt')) {
			echo 'History file not found.';
		} else {
			echo '<textarea class="editor" rows="30" cols="50" style="width:100%">';
			echo JFile::read(JPATH_COMPONENT_ADMINISTRATOR.'/changelog.txt');
			echo '</textarea>';
		}
		exit();
	}

	function addtag()
	{
		$user = JFactory::getUser();
		if ($user->authorise('core.create','com_tortags'))
		{
			$cid 	= JRequest::getInt('id',null);
			$tag 	= strip_tags(trim(JRequest::getVar('tag',null)));
			$option = strip_tags(trim(JRequest::getVar('comp',null)));
			
			if ((!$cid) || (!$tag) || (!$option)) 	{echo -1; die();}
			
			$db = JFactory::getDBO();
	
			$query = $db->getQuery(true);
			$query->select('`id`');
			$query->from('`#__tortags_tags`');
			$query->where('`title`='.$db->quote($tag));
			$db->setQuery($query);
			$tid = $db->loadResult();
			
			$query = $db->getQuery(true);
			$query->select('`id`');
			$query->from('`#__tortags_components`');
			$query->where('`component`='.$db->quote($option));
			$db->setQuery($query);
			$oid = $db->loadResult();
			
			if (!$tid)
			{
				$object = new stdclass();
				$object->title = $tag;
				$object->published = 1;
				$db->insertObject('#__tortags_tags', $object);
				$tid = (int)$db->insertid();	
			}else
			{
				$query = $db->getQuery(true);
				$query->select('`id`');
				$query->from('`#__tortags`');
				$query->where('`tid`='.$tid);
				$query->where('`oid`='.$oid);
				$query->where('`item_id`='.$cid);
				$db->setQuery($query);
				$id = $db->loadResult();
				if ($id) {echo -2; die();}
			}
			
			$object = new stdclass();
			$object->tid = $tid;
			$object->oid = $oid;
			$object->item_id = $cid;
			if ($db->insertObject('#__tortags', $object)) echo (int)$tid; 
			else {echo -3; }			
		}		
		die();
	}
	
	function deltag()
	{
		$user = JFactory::getUser();
		if ($user->authorise('core.delete','com_tortags'))
		{
			$tid 	= JRequest::getInt('tag_id',null);
			$cid 	= JRequest::getInt('id',null);
			$option = strip_tags(trim(JRequest::getVar('comp',null)));
			
			if ((!$cid) || (!$tid) || (!$option)) 	{echo -1; die();}
			
			$db = JFactory::getDBO();
			
			$query = $db->getQuery(true);
			$query->select('`id`');
			$query->from('`#__tortags_components`');
			$query->where('`component`='.$db->quote($option));
			$db->setQuery($query);
			$oid = $db->loadResult();
				
			$query = $db->getQuery(true);
			$query->delete('#__tortags');
			$query->where('`tid`='.$tid);
			$query->where('`oid`='.$oid);
			$query->where('`item_id`='.$cid);
			$db->setQuery($query);
			$db->query();
			echo 1;
		}
		die();
	}
	
	public function jxlabelsmigration()
	{
		$document = JFactory::getDocument();
	 	$document->addStyleSheet('components/com_tortags/assets/css/tortags.css');
		?>
		
					<?php										
					$db = JFactory::getDBO();					
					
					$query = "SHOW TABLES LIKE '%jxlabels_labels%'";
					$db->setQuery($query);
					$jxlabels_labels = $db->loadResult();
					$query = "SHOW TABLES LIKE '%jxlabels_maps%'";
					$db->setQuery($query);
					$jxlabels_maps = $db->loadResult();
					$query = "SHOW TABLES LIKE '%jxlabels_types%'";
					$db->setQuery($query);
					$jxlabels_types = $db->loadResult();	
					
					if ($jxlabels_labels && $jxlabels_maps && $jxlabels_types)
					{	
					?>
					<h3><?php echo JText::_('COM_TORTAGS_MIGRATION_STEP2');?></h3>
					<?php
						$db->setQuery("SELECT * FROM `".$jxlabels_labels."`");
						$list_objs = $db->loadObjectList();
						if (sizeof($list_objs))
						{							
							foreach ( $list_objs as $tags ) 
							{
								$db->setQuery("SELECT `id` FROM `#__tortags_tags` WHERE `title`='".$tags->title."'");
								$db->query();
								$tag_id = $db->loadResult();
								
								if (!$tag_id)
								{
									$db->setQuery("INSERT INTO `#__tortags_tags` (`id`, `title`,`published`) 
												VALUES ( NULL , '".$tags->title."','1' );");
									$db->query();
									$tag_id = $db->insertid();
									echo '<div class="tt_succ_import">New tag "'.$tags->title.'" successfully added</div>';
								}
								else echo '<div class="tt_succ_import">Tag "'.$tags->title.'" is already isset</div>';
							}
						}
						?>
						<h3><?php echo JText::_('COM_TORTAGS_MIGRATION_STEP3');?></h3>
						<?php
							
						$db->setQuery("SELECT * FROM `".$jxlabels_types."`");
						$list_objs = $db->loadObjectList();
						
						if (sizeof($list_objs))
						{							
							foreach ( $list_objs as $type ) 
							{
								$db->setQuery("SELECT `id` FROM `#__tortags_components` WHERE `component`='".$type->component."'");
								$db->query();
								$comp_id = $db->loadResult();
								
								if (!$comp_id)
								{
									$a=$b=array();
									$a[]=$type->component; $b[]='[COMPONENT]';
									$a[]='&id='; $b[]='&id=[ID]';
									
									$urltempl = str_replace($a,$b,$type->front_link);
									$db->setQuery("INSERT INTO `#__tortags_components` (`id`, `component`, `table`, `id_field`, `title_field`, `description_field`, `created_field`, `url_template`, `exc_views`,`be_key`,`fe_key`,`state_field`,`state_status`) 
												VALUES ( NULL , '".$type->component."', '".$type->table_name."','".$type->table_key."','".$type->table_title."','".$type->table_text."','".$type->table_date."','".$urltempl."', '', '".$type->admin_request_key."','".$type->site_request_key."','".$type->table_state."','".$type->table_pub_state."' );");
									$db->query();
									$comp_id = $db->insertid();
									echo '<div class="tt_succ_import">Component settings "'.$type->component.'" successfully added</div>';
								}else echo '<div class="tt_succ_import">Component settings for "'.$type->component.'" is already isset</div>';
								
								$db->setQuery("SELECT * FROM `".$jxlabels_maps."` WHERE `type_id`=".$type->type_id);
								$list_options = $db->loadObjectList();
								if (sizeof($list_options))
								{							
									foreach ( $list_options as $map ) 
									{	
										$db->setQuery("SELECT `title` FROM `".$jxlabels_labels."` WHERE `label_id`=".$map->label_id);
										$db->query();
										$jxtagtitle = $db->loadResult();
										
										$db->setQuery("SELECT `id` FROM `#__tortags_tags` WHERE `title`='".$jxtagtitle."'");
										$db->query();
										$tag_id = $db->loadResult();
																
										$db->setQuery("SELECT `id` FROM `#__tortags` WHERE `tid`='".$tag_id."' AND `oid`='".$comp_id."'  AND `item_id`='".$map->item_id."'");
										$map_id = $db->loadResult();
										
										if (!$map_id)
										{
																						
											$db->setQuery("INSERT INTO `#__tortags` (`id` ,`tid` ,`oid` ,`item_id`)
														VALUES ( NULL , '".$tag_id."', '".$comp_id."', '".$map->item_id."');");
											$db->query();
											$opt_id = $db->insertid();
											 echo '<p style="padding-left: 21px;"><div class="tt_succ_import">Link "'.$tag_id.'+'.$comp_id.'+'.$map->item_id.'" successfully added</div></p>';											
										}else echo '<p style="padding-left: 21px;"><div class="tt_succ_import">Link "'.$tag_id.'+'.$comp_id.'+'.$map->item_id.'" is already isset</div></p>';
								
									}
								}
								
							}
							
						}
						/*ITEMS aka (old)rows*/
					?>
					<div class="tt_succ_import"><b>Completed</b></div>
					<br />
					<small><?php echo JText::_('COM_TORTAGS_MIGRATION_STEP3_NOTE');?></small>										
					<?php
					}

	}
	
	public function jtmigration()
	{
		
		$document = JFactory::getDocument();
	 	$document->addStyleSheet('components/com_tortags/assets/css/tortags.css');
		?>
		
					<?php										
					$db = JFactory::getDBO();					
					
					$query = "SHOW TABLES LIKE '%tag_term'";
					$db->setQuery($query);
					$tag_term = $db->loadResult();
					$query = "SHOW TABLES LIKE '%tag_term_content%'";
					$db->setQuery($query);
					$tag_term_content = $db->loadResult();
					
					if ($tag_term && $tag_term_content)
					{	
					?>
					<h3><?php echo JText::_('COM_TORTAGS_MIGRATION_JOOMLATAG_STEP2');?></h3>
					<?php
						$db->setQuery("SELECT * FROM `".$tag_term."`");
						$list_objs = $db->loadObjectList();
						if (sizeof($list_objs))
						{							
							foreach ( $list_objs as $tags ) 
							{
								$db->setQuery("SELECT `id` FROM `#__tortags_tags` WHERE `title`='".$tags->name."'");
								$db->query();
								$tag_id = $db->loadResult();
								
								if (!$tag_id)
								{
									$db->setQuery("INSERT INTO `#__tortags_tags` (`id`, `title`,`published`) 
												VALUES ( NULL , '".$tags->name."','1' );");
									$db->query();
									$tag_id = $db->insertid();
									echo '<div class="tt_succ_import">New tag "'.$tags->name.'" successfully added</div>';
								}
								else echo '<div class="tt_succ_import">Tag "'.$tags->name.'" is already isset</div>';
							}
						}
						?>
						<h3><?php echo JText::_('COM_TORTAGS_MIGRATION_JOOMLATAG_STEP3');?></h3>
						<?php
							
						$db->setQuery("SELECT `id` FROM `#__tortags_components` WHERE `component`='com_content'");
						$db->query();
						$comp_id = $db->loadResult();
						
						$db->setQuery("SELECT * FROM `".$tag_term_content."`");
						$list_objs = $db->loadObjectList();
						
						if (sizeof($list_objs))
						{							
							foreach ( $list_objs as $map ) 
							{
										$db->setQuery("SELECT `name` FROM `".$tag_term."` WHERE `id`=".$map->tid);
										$db->query();
										$jtagtitle = $db->loadResult();
										
										$db->setQuery("SELECT `id` FROM `#__tortags_tags` WHERE `title`='".$jtagtitle."'");
										$db->query();
										$tag_id = $db->loadResult();
																
										$db->setQuery("SELECT `id` FROM `#__tortags` WHERE `tid`='".$tag_id."' AND `oid`='".$comp_id."' AND `item_id`='".$map->cid."' ");
										$map_id = $db->loadResult();
										
										if (!$map_id)
										{							
											$db->setQuery("INSERT INTO `#__tortags` (`id` ,`tid` ,`oid` ,`item_id`)
														VALUES ( NULL , '".$tag_id."', '".$comp_id."', '".$map->cid."');");
											$db->query();
											$opt_id = $db->insertid();
											 echo '<p style="padding-left: 21px;"><div class="tt_succ_import">Link "'.$tag_id.'+'.$comp_id.'+'.$map->cid.'" successfully added</div></p>';											
										}else echo '<p style="padding-left: 21px;"><div class="tt_succ_import">Link "'.$tag_id.'+'.$comp_id.'+'.$map->cid.'" is already isset</div></p>';
								
									}
							
						}
					?>
					<div class="tt_succ_import"><b>Completed</b></div>										
					<?php
					}

	
	}
	
	public function addattach()
	{
		$user = JFactory::getUser();
		if ($user->authorise('core.create','com_tortags'))
		{
			$otid 	= JRequest::getInt('tid',null);
			$tag 	= strip_tags(trim(JRequest::getVar('tag',null)));
						
			if ((!$otid) || (!$tag)) 	{echo -1; die();}
			
			$db = JFactory::getDBO();
	
			$query = $db->getQuery(true);
			$query->select('`id`');
			$query->from('`#__tortags_tags`');
			$query->where('`title`='.$db->quote($tag));
			$db->setQuery($query);
			$tid = $db->loadResult();
			
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('`#__tortags`');
			$query->where('`tid`='.$otid);
			$db->setQuery($query);
			$cons = $db->loadObjectList();
			
			if (!$tid)
			{
				$object = new stdclass();
				$object->title = $tag;
				$object->published = 1;
				$db->insertObject('#__tortags_tags', $object);
				$tid = (int)$db->insertid();	
			}
			{
				if (sizeof($cons))
				foreach ( $cons as $con ) 
				{
					$query = $db->getQuery(true);
					$query->select('`id`');
					$query->from('`#__tortags`');
					$query->where('`tid`='.$tid);
					$query->where('`oid`='.$con->oid);
					$query->where('`item_id`='.$con->item_id);
					$db->setQuery($query);
					$id = $db->loadResult();
					
					if (!$id) 
					{
						$object = new stdclass();
						$object->tid = $tid;
						$object->oid = $con->oid;
						$object->item_id = $con->item_id;
						$l=$db->insertObject('#__tortags', $object); 			
					}
				}
				if (!$l) echo -3;				
			}
			echo $tid;			
		}		
		die();
	}
}
