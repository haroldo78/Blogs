<?php
/*
* @name smartrelatedarticles 1.0
* Created By Guarneri Iacopo
* http://www.the-html-tool.com/
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/
 
defined('_JEXEC') or die;

class plgButtonsmartrelatedarticles extends JPlugin
{
	public function onDisplay($name)
    	{
		$n_art = $this->params->get('n_art', '3');
		$cats = implode(",",$this->params->get('cats', '1'));
		$link='../plugins/editors-xtd/smartrelatedarticles/dialog.php?name='.$name.'&n_art='.$n_art.'&cats='.$cats;
		
		JHTML::_('behavior.modal');
		$button = new JObject();
		if(substr(JVERSION,0,1)=="2"){
			$button->set('modal', true);
			$button->set('link', $link);
			$button->set('text', 'Related article');
			$button->set('name', 'blank');
			$button->set('options', "{handler: 'iframe', size: {x: 570, y: 400}}");
		}
		else{
			$button->modal = true;
			$button->class = 'btn';
			$button->link = $link;
			$button->text = 'Related article';
			$button->name = 'blank';
			$button->options = "{handler: 'iframe', size: {x: 570, y: 400}}";	
		}
		return $button;
    }
}
?>
