<?php
/**
* TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.0
* @package TorTags
* @author Tormix Team
* @Copyright Copyright (C) Tormix, www.tormix.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die;
$Itemid = JRequest::getInt('Itemid');
?>
<div class="tortags<?php echo $moduleclass_sfx; ?>" id="tortags-mod<?php echo $moduleclass_sfx; ?>">
<?php foreach ($list as $item) {	?>
	<?php 
		if ($item['cloud']>9) $item ['cloud']=9;
	
	$tagclass = "tag" . $item['cloud'];
	$tagname = $item['name'];
	echo "<a class='".$tagclass."' href=\"" . JRoute::_("index.php?option=com_search&searchword=" . $tagname . "&areas[0]=tortags") . "\">$tagname</a> ";
	
	 } ?>
</div>
