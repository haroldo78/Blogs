<?php
/**
* TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.0
* @package TorTags
* @author Tormix Team
* @Copyright Copyright (C) Tormix, www.tormix.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
if (TT_JVER==3)
{
	?>
	<style type="text/css">
		.adminformlist
		{
			list-style: none outside none !important;
		}
		.adminformlist label 
		{
		    display: inline-block;
		    width: 180px;
		}
		.tabs span h3
		{
			-moz-border-bottom-colors: none;
		    -moz-border-left-colors: none;
		    -moz-border-right-colors: none;
		    -moz-border-top-colors: none;
		    background-color: #FFFFFF;
		    border-color: #DDDDDD #DDDDDD transparent;
		    border-image: none;
		    border-radius: 4px 4px 4px 4px;
		    border-style: solid;
		    border-width: 1px;
		    clear: right;
		    color: #555555;
		    cursor: default;
		    display: inline-block;
		    float: left;
		    margin: 1px;
		    padding: 1px;
		    font-size:13px;
		}
		.item-details
		{
			cursor: pointer;
		    display: inline-block;
		    float: left;
		    width: auto;
		}
		dl
		{
			display: inline-block;
			width: 100%;
		}
	</style>
	<?php
}
?>
<table class="admin table table-striped" width="100%">
	<tbody>
		<tr>
			<td valign="top" width="100%" >			<?php echo JHtml::_('tabs.start','import-export-tab', array('useCookie'=>1));?>
				<?php echo JHtml::_('tabs.panel',JText::_('COM_TORTAGS_IMPEXP_JXLABELS'), 'jxlabels-impexp');?>		
				<div class="clr"></div>
					<br/>
					<div>
						<fieldset class="settingfieldset">
						<legend><?php echo JText::_('COM_TORTAGS_IMPORT_JXLABELS'); ?></legend>
						<table cellspacing="1" class="adminlist table table-striped">
							<tr class="row0">
								<div style="color:red;"><?php echo JText::_('COM_TORTAGS_IMPEXP_JXLABELS_NOTE'); ?></div>
								<h4><?php echo JText::_('COM_TORTAGS_IMPEXP_JXLABELS_STEPONE');?></h4>
								<?php
										
									$db = JFactory::getDBO();
																		
										$query = "SHOW TABLES LIKE '%jxlabels_labels%'";
										$db->setQuery($query);
										$jxlabels_labels = $db->loadResult();
									if ($jxlabels_labels) echo '<div class="tt_succ_import">'.$jxlabels_labels.'</div>'; else echo '<div class="tt_error_import"> jxlabels_labels '.JText::_('COM_TORTAGS_MIGRATION_TNF').'</div>';										
									
										$query = "SHOW TABLES LIKE '%jxlabels_maps%'";
										$db->setQuery($query);
										$jxlabels_maps = $db->loadResult();
									if ($jxlabels_maps) echo '<div class="tt_succ_import">'.$jxlabels_maps.'</div>'; else echo '<div class="tt_error_import"> jxlabels_maps '.JText::_('COM_TORTAGS_MIGRATION_TNF').'</div>';
										
										$query = "SHOW TABLES LIKE '%jxlabels_types%'";
										$db->setQuery($query);
										$jxlabels_types = $db->loadResult();
									if ($jxlabels_types) echo '<div class="tt_succ_import">'.$jxlabels_types.'</div>'; else echo '<div class="tt_error_import"> jxlabels_types '.JText::_('COM_TORTAGS_MIGRATION_TNF').'</div>';
									
									if ($jxlabels_labels && $jxlabels_maps && $jxlabels_types)
									{	
									?>
									<br />
									<div class="button2-left">
									<div class="blank">
										<a class="modal btn btn-small" rel="{handler: 'iframe'}" href="index.php?option=com_tortags&amp;task=jxlabelsmigration&amp;tmpl=component">
											<?php echo JText::_('COM_TORTAGS_MIGRATION_START');?>
										</a>
									</div>
									</div>
									<?php } ?>
							</tr>
						</table>						
					</fieldset>
					</div>
				<div class="clr"></div>
				<?php echo JHtml::_('tabs.panel',JText::_('COM_TORTAGS_IMPEXP_JOOMLATAG'), 'jt-impexp');?>		
				<div class="clr"></div>
					<br/>
					<div>
						<fieldset class="settingfieldset">
						<legend><?php echo JText::_('COM_TORTAGS_IMPEXP_JOOMLATAG'); ?></legend>
						<table cellspacing="1" class="adminlist table table-striped">
							<tr class="row0">
								<div style="color:red;"><?php echo JText::_('COM_TORTAGS_IMPEXP_JOOMLATAG_NOTE'); ?></div>
								<h4><?php echo JText::_('COM_TORTAGS_IMPEXP_JOOMLATAG_STEPONE');?></h4>
								<?php
										
									$db = JFactory::getDBO();
										
								
										$query = "SHOW TABLES LIKE '%tag_term'";
										$db->setQuery($query);
										$tag_term = $db->loadResult();
									if ($tag_term) echo '<div class="tt_succ_import">'.$tag_term.'</div>'; else echo '<div class="tt_error_import"> tag_term '.JText::_('COM_TORTAGS_MIGRATION_TNF').'</div>';										
									
										$query = "SHOW TABLES LIKE '%tag_term_content%'";
										$db->setQuery($query);
										$tag_term_content = $db->loadResult();
									if ($tag_term_content) echo '<div class="tt_succ_import">'.$tag_term_content.'</div>'; else echo '<div class="tt_error_import"> tag_term_content '.JText::_('COM_TORTAGS_MIGRATION_TNF').'</div>';
										
									if ($tag_term_content && $tag_term)
									{	
									?>
									<br />
									<div class="button2-left">
									<div class="blank">
										<a class="modal btn btn-small" rel="{handler: 'iframe'}" href="index.php?option=com_tortags&amp;task=jtmigration&amp;tmpl=component">
											<?php echo JText::_('COM_TORTAGS_MIGRATION_START');?>
										</a>
									</div>
									</div>
									<?php } ?>
							</tr>
						</table>						
					</fieldset>
					</div>
				<div class="clr"></div>
				
				<?php echo JHtml::_('tabs.end'); ?>
			</td>
		</tr>
	</tbody>
</table>
