<?php
/**
* TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.0
* @package TorTags
* @author Tormix Team
* @Copyright Copyright (C) Tormix, www.tormix.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
if (TT_JVER!=3) { JHtml::_('behavior.mootools');}
JHtml::_('behavior.formvalidation');
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
<script type="text/javascript">

function addAttach(tid)
{
	var tag = $('attag').get('value');
	if (!tid) return;
	var exploded = tag.split(',');
	Array.each(exploded, function(tag, index)
	{
 		var url = 'index.php?option=com_tortags&task=addattach&tag='+ tag +'&tid='+tid;
		var a = new Request.HTML({
		         url: url,
		         method: 'post',
		         update   : $('tt-value'),
		         onRequest: function(){
					$('tt-status').set('text', '<?php echo JText::_('PLG_TORTAGS_LOADING');?>');
				},
		         onComplete:  function(response) 
		            {
		            	var result = $('tt-value').get('text');
		            	var mess = '';
		            	
		            	if (result==-1)
		            	{
		            		mess ="<span class='tt_error'><?php echo JText::_('PLG_TORTAGS_NOTICE_ERROR1');?></span>";
		            	}else
		            	if (result=='-2')
		            	{
		            		mess ="<span class='tt_notice'><?php echo JText::_('PLG_TORTAGS_NOTICE_DUBLICATE');?></span>";
		            	}else
		            	if (result=='-3')
		            	{
		            		mess ="<span class='tt_error'><?php echo JText::_('PLG_TORTAGS_NOTICE_ERROR2');?></span>";
		            	}
		            	else
		            	{
		            		mess ="<span class='tt_success'><?php echo JText::_('PLG_TORTAGS_NOTICE_ADDED');?></span>";		            		
		            	}
		            	
		            	$('tt-status').set('html', mess);
		            	$('attag').set('value', '');
		            }
		        }); 
		if (tag) a.send();					
	});
}
</script>
<table class="admin table table-striped" width="100%">
	<tbody>
			<td valign="top" width="100%" >
				<form action="<?php echo JRoute::_('index.php?option=com_tortags&view=tag&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data" >
					<?php
						echo JHtml::_('tabs.start','item-tabs');
						foreach ($this->form->getFieldsets() as $fieldset) {
						$fields = $this->form->getFieldset($fieldset->name);
						if (count($fields) > 0) {
						echo JHtml::_('tabs.panel',JText::_($fieldset->label), 'item-'.$fieldset->name);
					?>
						<fieldset class="adminform" >
							<ul class="adminformlist">
							<?php
								foreach($this->form->getFieldset($fieldset->name) as $field) {
							?>
								<li><?php echo $field->label; echo $field->input; ?></li>
							<?php } ?>
							</ul>
							<br class="clr" />
					<?php }} 
					if (isset($this->item->id))
					{
					 echo JHtml::_('tabs.panel',JText::_('COM_TORTAGS_CONNECTED'), 'item-atach-tag');
					 ?>
					 "<?php echo $this->item->title;?>".<br/>
					 <?php echo JText::_('COM_TORTAGS_CONNECTED_INFO');?>
					 <br class="clr" />
					 <fieldset class="adminform" >
					 <legend><div style="display:none" id="tt-value"></div><div id="tt-status"></div></legend>
					 	<input type="text" class="text" id="attag" name="attag" value="" size="30"/>
					 	<input type="button" class="button" name="addatttachtag" onclick="javascript:addAttach(<?php echo $this->item->id;?>);" value="<?php echo JText::_('COM_TORTAGS_ADD');?>" />
					 </fieldset>
					 
					 <?php 
					}
					 echo JHtml::_('tabs.end'); 
					?>
					<div>
						<input type="hidden" name="task" value="item.edit" />
						<?php echo JHtml::_('form.token'); ?>
					</div>
				</form>
			</td>
		</tr>
	</tbody>
</table>

