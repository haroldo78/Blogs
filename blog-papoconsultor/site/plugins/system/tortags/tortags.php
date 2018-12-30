<?php
/**
* TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.0
* @package TorTags
* @author Tormix Team
* @Copyright Copyright (C) Tormix, www.tormix.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgSystemtortags extends JPlugin
{
	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage('plg_system_tortags');
	}

	private function getArticleByTitleMaxId($aid=0)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('`title`');
		$query->from('`#__content`');
		$query->where('`id`='.(int)$aid);
		$db->setQuery($query);
		$r = $db->loadResult();
		
		if ($r)
		{
			$word	= $db->Quote($db->getEscaped($r, true).'%', false);
			$where	= '`title` LIKE '.$word;			
			$query = $db->getQuery(true);
			$query->select('`id`');
			$query->from('`#__content`');
			$query->where($where);
			$query->order('`id` DESC');
			$db->setQuery($query);
			$aid2 = $db->loadResult();
			if ($aid2!=$aid && $aid2>$aid) return $aid2; 
		}
		return null;		
	}	
	
	private function addNewBatchTag($tid=0,$itemid=0,$oid=0)
	{
		$user = JFactory::getUser();
		if ($user->authorise('core.create','com_tortags'))
		{
			$db = JFactory::getDBO();
	
			$query = $db->getQuery(true);
			$query->select('`id`');
			$query->from('`#__tortags`');
			$query->where('`tid`='.(int)$tid);
			$query->where('`oid`='.(int)$oid);
			$query->where('`item_id`='.(int)$itemid);
			$db->setQuery($query);
			$id = $db->loadResult();
			if (!$id && $tid && $itemid )
			{
				$object = new stdclass();
				$object->tid = $tid;
				$object->oid = $oid;
				$object->item_id = $itemid;
				$db->insertObject('#__tortags', $object); 
			}
		}
	}
	

	function onAfterInitialise()
	{
				
		$task = JRequest::getVar('task');
		if ($task=='article.save2copy')
		{
			$jform = JRequest::getVar('jform');
			if (isset($jform['id']))
			{
				$_SESSION['tt_copy'] = (int)$jform['id'];
			}
		}else
		{
			if (isset($_SESSION['tt_copy']))
			{
				$aid = (int) $_SESSION['tt_copy']; 
				$c = $this->getCompDetails('com_content');
				$cid = $c->id;
				if ($cid)
				if ($aid)
				{
					$cur_tags = $this->getTags($aid, 'com_content');
						
						if (sizeof($cur_tags))
						{
							$aid2 = $this->getArticleByTitleMaxId($aid);
							
							if ($aid2)
							{
								foreach ( $cur_tags as $tag ) 
								{
									$this->addNewBatchTag($tag->id,$aid2,$cid);
								}
							}
						}
					$_SESSION['tt_copy']='';
				}
			}
		}
		if ($task=='article.batch') 
		{
			$input	= JFactory::getApplication()->input;
			$vars	= $input->post->get('batch', array(), 'array');
			$cid	= $input->post->get('cid', array(), 'array');		
			if ($vars['move_copy']=='c') $_SESSION['tt_batch'] = $cid;
		}else
		{
			if (isset($_SESSION['tt_batch']))
			{
				$ttbatch=(array)$_SESSION['tt_batch'];
				if (sizeof($ttbatch))
				{
					$c = $this->getCompDetails('com_content');
					$cid = $c->id;
					if ($cid)
					
					
					foreach ($ttbatch as $aid ) 
					{
						$cur_tags = $this->getTags($aid, 'com_content');
						
						if (sizeof($cur_tags))
						{
							$aid2 = $this->getArticleByTitleMaxId($aid);
							
							if ($aid2)
							{
								foreach ( $cur_tags as $tag ) 
								{
									$this->addNewBatchTag($tag->id,$aid2,$cid);
								}
							}
						}
					}
					$_SESSION['tt_batch']='';
				}
				
			}
		}
		
		 $option = JRequest::getVar('option');
		 $view	 = JRequest::getVar('view');
		 $layout = JRequest::getVar('layout');
		 $a_id 	 = JRequest::getInt('a_id');
		 $option	= JRequest::getVar('option');
		 $components 	= $this->getAlloweComponents();		
		 if (sizeof($components))
		 if (in_array($option,$components) || ($view=='form' && $layout=='edit' && $a_id>0))
		 { 
			$app = JFactory::getApplication();
			$settings = JComponentHelper::getParams('com_tortags');
			$style = 'default.css';	 	
			JFactory::getDocument()->addStylesheet(JURI::root().'components/com_tortags/assets/css/'.$style);
		 }
	}
	
	function onAfterRender()
	{
		 $option = JRequest::getVar('option');
		 $view	 = JRequest::getVar('view');
		 $layout = JRequest::getVar('layout');
		 $body 		= JResponse::getBody();
		 $img 		= JURI::root().'components/com_tortags/assets/images/tags.png';
		 $img_add 	= JURI::root().'components/com_tortags/assets/images/tag_add.png';
		 $img_del 	= JURI::root().'components/com_tortags/assets/images/delete.png';
		 $img_info	= JURI::root().'components/com_tortags/assets/images/about.png';
		 $img_but	= JURI::root().'components/com_tortags/assets/images/tt_button_left.png';
		 $img_blank	= JURI::root().'components/com_tortags/assets/images/tt_button_blank.png';
		 		 
		 $components = $this->getAlloweComponents();
		
		if (sizeof($components))
		 if (in_array($option,$components))
		 {
		 			 	
		 	$app = JFactory::getApplication();
		 	$isAdmin = $app->isAdmin();
		 	$component = $this->getCompDetails($option);
		 	
		 	$task = JRequest::getVar('task');
		 	$controller = JRequest::getVar('controller');
		 	if ($controller && $task) $task = $controller.'.'.$task;
		 	
		 	if (isset($component->exc_views) && ($view || $task))
		 	{
		 		$views = explode(',',trim($component->exc_views));

		 		if (sizeof($views))
		 		{
		 			foreach ( $views as $exc ) 
		 			{
		 				$exc = trim($exc);
		 				if ($exc) 
		 				{
		 					if ($view==$exc) return;
		 					if ($task==$exc) return;
		 				}
		 			}
		 		}
		 	} 
			 if (($component->where_enable==2) && $isAdmin) return;
			 if (($component->where_enable==1) && !$isAdmin) return;
			
			if ($isAdmin)
			{
				if (isset($component->be_key)) 
				$id = JRequest::getInt($component->be_key);
				
			}else
			{
				if (isset($component->fe_key)) 
				$id = JRequest::getInt($component->fe_key);				
			}
			
			if (!$id) $id = JRequest::getInt($component->id_field);
		 	$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		 	$aid = JRequest::getInt('a_id');
		 
		 	if (!$id && isset($cid[0])) $id = $cid[0];
		 	if (!$id && $aid) $id = $aid;
			if ($option=='com_content' && $view=='form' && $aid) $id = $aid;

			$vmapply = JRequest::getVar('vmapply');
			
		 	if ($component->component=='com_virtuemart' && $vmapply==1) 
			 {
				$cvm = JRequest::getVar( 'virtuemart_product_id' , array() , '' , 'array' );		
				if (isset($cvm[0])) $id = (int)$cvm[0];
			 }
			 if ($component->component=='com_sobipro') 
			 {
			 	$sid = JRequest::getInt('sid');
			 	if (!$id && $sid) $id = $sid;
			 	
			 }
		 	$tags	= $this->getTags($id, $option);
		 	
		 	$return='';

		 	$return	=  '<div style="display:none" id="tt-value"></div><div id="tt-status"></div><input type="text" class="tt_inpval" id="ttnewtag" name="ttnewtag" value="" size="20" maxlength="70" />';
		 	$return	.= '<div class="tt_button"><div class="tt_end"><a href="javascript:void(0);" onclick="addTag();"><img src="'.$img_add.'"/>'.JText::_('PLG_TORTAGS_TITLE_ADD_TAGS').'</a></div></div>';

		 	$return	.= '<div id="tt-tags" class="tt-tags">';
		 	if (sizeof($tags))
			{
			 	foreach ( $tags as $tag ) 
			 	{		 		
			 		$return .='<div id="tagid_'.$tag->id.'" class="tt_button">' .
			 				'<div class="tt_end">' .
			 					'<span class="tt-2gtr"><a class="tt-del-link" href="javascript:void(0);" onclick="javascript:delTag('.$tag->id.');">' .
			 						'<img src="'.$img_del.'"/></a>' .
			 								'<span style="font-weight: normal;">'.$tag->title.'</span></span>' .
			 				'</div>' .
			 				'</div>';
			 	}
			 }
		 	$return	.= '</div>';
		 	$addurl = '';
		 	if (JURI::current()==JURI::root().'administrator/index.php') $addurl = 'administrator/';
			 	$curl = JURI::root().$addurl.'index.php?option=com_tortags&task=addtag&tmpl=component&format=raw';
			 	$durl = JURI::root().$addurl.'index.php?option=com_tortags&task=deltag&tmpl=component&format=raw';
			 	ob_start();
			 	?>
			 	<script type="text/javascript">
	                 
					  function addTag()
						{ if (typeof jQuery === 'function') { jQuery.noConflict();}
							var tag = $('ttnewtag').get('value');
					 		var url = '<?php echo $curl;?>&tag='+ tag +'&id=<?php echo $id;?>&comp=<?php echo $option;?>';
							var a = new Request.HTML({
							         url: url,
							         method: 'post',
							         update   : $('tt-value'),
							         onRequest: function(){
	        							$('tt-status').set('text', 'loading...');
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
							            		var button = '<div id="tagid_'+ result +'" class="tt_button"><div class="tt_end"><span class="tt-2gtr"><a class="tt-del-link" href="javascript:void(0);" onclick="javascript:delTag('+ result +');"><img src="<?php echo $img_del;?>"/></a><span style="font-weight: normal;">'+ tag +'</span></span></div></div>';
							            		$('tt-tags').set('html', $('tt-tags').get('html') + button);
							            	}
							            	
							            	$('tt-status').set('html', mess);
							            	$('ttnewtag').set('value','');
							            	$('ttnewtag').focus();
	
							            }
							        }); 
							a.send(); 
						}
						function delTag(id)
						{if (typeof jQuery === 'function') { jQuery.noConflict();}
					 		var url = '<?php echo $durl;?>&tag_id='+ id+'&id=<?php echo $id;?>&comp=<?php echo $option;?>';
							var d = new Request.HTML({
							         url: url,
							         method: 'post',
							         update   : $('tt-value'),
							         onRequest: function(){
	        							$('tt-status').set('text', 'loading...');
	    							},
							         onComplete:  function(response) 
							            {	
							            	var namefield = 'tagid_'+ id;
							            	$(namefield).destroy();
							            	var result = $('tt-value').get('text');
							            	var mess = '';
							            	
							            	if (result==-1)
							            	{
							            		mess ="<span class='tt_error'><?php echo JText::_('PLG_TORTAGS_NOTICE_ERROR1');?></span>";
							            	}else
							            	{
							            		mess ="<span class='tt_success'><?php echo JText::_('PLG_TORTAGS_NOTICE_DELETED');?></span>";
							            	}
							            	$('tt-status').set('html', mess);
							            }
							        }); 
							d.send(); 
						}
	 			</script>
			 	<?php
			 	$script = ob_get_contents();
			 	ob_end_clean();
				 if (!$id) 
		 {
		 	//$script='';
		 	if ($option=='com_content')
		 	$return	=  '<div style="display: inline-block;">
		 					<div class="tt_button">
		 						<div class="tt_end">
		 								<span class="tt-2gtr"><img src="'.$img_add.'"/>'.JText::_('PLG_TORTAGS_TITLE_NEW_TAGS').' &nbsp;</span>
		 						</div>
		 					</div>
		 					<input type="text" class="tt_inpval" id="ttnewtag" name="ttnewtags" value="" size="40" maxlength="300" />'
		 					.JHTML::tooltip(JText::_('PLG_TORTAGS_NOTICE_NEW_TAGS'),JText::_('PLG_TORTAGS_NOTICE_SEPARATE'),JURI::root().'components/com_tortags/assets/images/about.png').'
		 				<br/>
		 				</div>';
		 	else $return	= "<div id='tt-status'><span class='tt_notice_new'>".JText::_('PLG_TORTAGS_NOTICE_NEW')."</span></div>";
		 }

			$body = str_replace('</body', $script.'</body', $body);
		 	$body = str_replace('<div id="editor-xtd-buttons"', $return.'<div id="editor-xtd-buttons"', $body);
		 }
		 JResponse::setBody($body);
	}

	protected function getAlloweComponents()
	{
		$db = JFactory::getDBO();
			
			$query = $db->getQuery(true);
			$query->select('`component`');
			$query->from('`#__tortags_components`');
			$db->setQuery($query);
			$cmpts = $db->loadColumn();
		return $cmpts;	
	}
	
		private function getCompDetails($option='com_content')
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('`#__tortags_components`');
		$query->where('`component`='.$db->quote($option));
		$db->setQuery($query);
		$c = $db->loadObject();
		return $c;		
	}

	
	protected function getTags($id, $option)
	{
		$db = JFactory::getDBO();
		
		$query = $db->getQuery(true);
		$query->select('`id`');
		$query->from('`#__tortags_components`');
		$query->where('`component`='.$db->quote($option));
		$db->setQuery($query);
		$oid = $db->loadResult();
			
		$query = $db->getQuery(true);
		$query->select('`t`.*');
		$query->from('`#__tortags_tags` AS `t`');
		$query->join('INNER','`#__tortags` AS `m` ON `m`.`tid`=`t`.`id`');
		$query->where('`m`.`item_id`='.(int)$id);
		$query->where('`m`.`oid`='.(int)$oid);
		$query->where('`t`.`published`=1');
		$query->order('`t`.`title` ASC');
		$db->setQuery($query);
		$tags = $db->loadObjectList();
		return $tags;
	}
}