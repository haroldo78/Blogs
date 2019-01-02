<?php
/**
* TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.0
* @package TorTags
* @author Tormix Team
* @Copyright Copyright (C) Tormix, www.tormix.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted Access');

$imgpath = JURI::root().'/administrator/components/com_tortags/assets/images/';
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

?>
<table width="80%" border="0" class="table table-striped">
		<tbody><tr>
			<td width="15%">
				<img src="<?php echo $imgpath; ?>development.png" />
			</td>
			<td>
				<h1><?php echo JText::_('COM_TORTAGS');?> 
					<img src="<?php echo $imgpath;?>j16_native.png" />
					<img src="<?php echo $imgpath;?>j17_native.png" />
					<img src="<?php echo $imgpath;?>j25_native.png" />
					<img src="<?php echo $imgpath;?>j30_native.png" />
				</h1>
				<span class="copyright">[ <?php echo JText::_('COM_TORTAGS_DESCRIPTION');?> ]</span>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_TORTAGS_VERSION');?>:</td>
			<td>
				<div class="button2-left">
					<div class="blank">
						&nbsp;<?php echo TorTagsHelper::getVersion();?>&nbsp;
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_TORTAGS_SITE');?>:</td>
			<td>
				<div class="button2-left">
					<div class="blank">
						<a target="_blank" class="btn btn-small" href="http://tormix.com">http://tormix.com</a>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_TORTAGS_SUPPORT');?>:</td>
			<td>
				<div class="button2-left">
					<div class="blank">
						<a  class="btn btn-small" href="mailto:support@tormix.com">support@tormix.com</a>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_TORTAGS_CHANGELOG');?>:</td>
			<td>
				<div class="button2-left">
					<div class="blank">
						<a class="modal btn btn-small" rel="{handler: 'iframe'}" href="index.php?option=com_tortags&amp;task=history&amp;tmpl=component">
							 TorTags version history
						</a>
					</div>
				</div>
			</td>
		</tr>
				<tr>
			<td>Say thank you :)</td>
			<td>
			 Just buy pro version ;)
			</td>
		</tr>
		
		<tr>
			<td>
			<a target="_blank"  href="http://extensions.joomla.org/extensions/search-a-indexing/tags-a-clouds/18853" title="">
				<img src="<?php echo $imgpath; ?>joomla_extensions.png" alt="" border="0"/>
			</a>
			
			</td>
			<td>
				<div class="button2-left">
					<div class="blank">
						<a class="btn btn-small" target="_blank" href="http://extensions.joomla.org/extensions/search-a-indexing/tags-a-clouds/18853/review" >Your review is very important to us. Thank you!</a>
					</div>
				</div>
			</td>
		</tr>
	</tbody>
</table>