<?php
/**
* TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.0
* @package TorTags
* @author Tormix Team
* @Copyright Copyright (C) Tormix, www.tormix.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');


class plgButtonTortags extends JPlugin
{
	
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	
	public function onDisplay($name)
	{
		$app = JFactory::getApplication();
		$doc		= JFactory::getDocument();
		$template	= $app->getTemplate();
 		$option = JRequest::getVar('option');
 		 $db = JFactory::getDBO();
			$oid = null;
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('`#__tortags_components`');
			$query->where('`component`="'.$option.'"');
			$db->setQuery($query);
			$obj_comp = $db->loadObject();
			if (isset($obj_comp->id)) $oid = $obj_comp->id;
 		 if (!$oid) return false;
 		 
 		 $id = JRequest::getVar($obj_comp->id_field);
		 if (is_array($id) && $option=='com_virtuemart')
		 {
			if (isset($id[0])) $id=$id[0];
		 }
 		 if (!$id) $id = JRequest::getVar('id');
 		 if (!$id && $option=='com_sobipro') $id = JRequest::getVar('sid');
		 $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		 $aid = JRequest::getVar('a_id');
		 if (!$id && isset($cid[0])) $id = $cid[0];
		 if (!$id && $aid) $id = $aid;
		 if (!$id && $option!="com_content") return null;
		 if (!$id) $id='[new_id]';
		 
		$js = "
			function insertTorTags(editor) {
					jInsertEditorText('{tortags,".$id.",".(int)$oid."}', editor);
			}
			";

		$doc->addScriptDeclaration($js);

		$button = new JObject;
		$button->set('modal', false);
		$button->set('onclick', 'insertTorTags(\''.$name.'\');return false;');
		$button->set('text', JText::_('PLG_TORTAGS_BUTTON_TORTAGS'));
		$button->set('name', 'pagebreak');
		$button->set('link', '#');

		return $button;
	}
}
