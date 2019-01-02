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

jimport('joomla.plugin.plugin');

class plgContentegoltvoting extends JPlugin
{
	
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		
		// Load language strings
		$this->loadLanguage();
	}

	public function onContentPrepare ($context, &$article, &$params, $limitstart)
	{
		$document = JFactory::getDocument();
		
		// Add stylesheet
		$document->addStyleSheet(JURI::root(true).'/media/egoltvoting/css/egoltvoting.css');
		$document->addStyleSheet(JURI::root(true).'/media/egoltvoting/css/tooltipster.css');
		// $document->addStyleSheet(JURI::root(true).'/media/egoltvoting/css/themes/tooltipster-light.css');
		$ldset = $this->params->get('ldicons', 1);
		// $ldcss = JURI::root(true).'/media/egoltvoting/images/sets/'.$ldset.'/style.css';
		// $ldfile = JPATH_SITE.'/media/egoltvoting/images/sets/'.$ldset.'/style.css';
		// if(file_exists($ldfile))
			// $document->addStyleSheet($ldcss);
		

		// Add ajax script processor
		$document->addScript(JURI::root(true).'/media/egoltvoting/js/ajax.js');	
			
		// Add jQuery if needed		
		if(version_compare(JVERSION, '3.0', 'ge'))
		{
			JHtml::_('behavior.framework');	
		}
		else
		{
			$needJquery = true;
			$header = $document->getHeadData();
			foreach($header['scripts'] as $scriptName => $scriptData)
			{
				if(substr_count($scriptName,'/jquery'))
				{
					$needJquery = false;
				}
			}
			if($needJquery)
				$document->addScript(JURI::root(true).'/media/egoltvoting/js/jquery.min.js');	
		}
			
		$document->addScript(JURI::root(true).'/media/egoltvoting/js/jquery.color.min.js');
		$document->addScript(JURI::root(true).'/media/egoltvoting/js/jquery.tooltipster.min.js');
		$document->addScript(JURI::root(true).'/media/egoltvoting/js/egoltvoting.js');

		$document->addScriptDeclaration( "var rooturi = '".JURI::base(true)."';");
		$document->addScriptDeclaration( "var eg_th_str = '". JText::_('PLG_CONTENT_EGOLTVOTING_THANKS') ."';");
		$document->addScriptDeclaration( "var eg_vt_str = '". JText::_('PLG_CONTENT_EGOLTVOTING_VOTED_BEFORE') ."';");
		$document->addScriptDeclaration( "var eg_ac_str = '". JText::_('PLG_CONTENT_EGOLTVOTING_NOACCESS') ."';");
	}

	public function egoltvotingModeling($article, $params) {
		// Get article id
		$id = $article->id;
		
		// Select like status of content
		$db	= JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__egoltvoting');
		$query->where('cid = '.$db->quote($id));
		$query->where('service = '.$db->quote(1));
		$db->setQuery( (string)$query );
		$res = $db->loadObject();
		
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__content_rating');
		$query->where('content_id = '.$db->quote($id));
		$db->setQuery( (string)$query );
		$jres = $db->loadObject();
		
		
		// Set default value for new items
		if(empty($res) and empty($jres))
		{
			$res = new stdClass();
			$res->counter = 0;
			$res->sum = 0;		
		}
		// Sync with Joomla! contents rating
		elseif(@$res->sum != @$jres->rating_sum)
		{
			$query = $db->getQuery(true);
			// row does not exist
			if(empty($res))
			{
				$res = new stdClass();
				$query->insert('#__egoltvoting');
			}
			// row is out of date
			else
			{
				$query->update('#__egoltvoting');			
			}
			$query->set('cid = '.$db->quote($id));
			$query->set('service = '.$db->quote(1));
			$query->set('lastip = '.$db->quote(@$jres->lastip));
			$query->set('lastdate = NOW()');
			$query->set('counter = '.$db->quote(@$jres->rating_count));
			$query->set('sum = '.$db->quote(@$jres->rating_sum));
			$db->setQuery( (string)$query );
			$db->execute();
			
			$res->sum = $jres->rating_sum;
			$res->counter = $jres->rating_count;
			$res->lastip = $jres->lastip;
		}
		
		return $res;
	}
		
	public function egoltvotingPrepare ($article, $params)
	{
		$input	= JFactory::getApplication()->input;
		$id = $article->id;
		
		// View Restriction
		$view = $input->get('view');
		$showinart = $this->params->get('showinart', 1);
		$showincat = $this->params->get('showincat', 1);
		if( ($view == 'article') and (!$showinart) )
			return false;
		if( ($view == 'category') and (!$showincat) )
			return false;	

		// Categories and Articles filter
		if($this->params->get('encats') or $this->params->get('discats') or $this->params->get('disarts'))
		{
			$db	= JFactory::getDBO();
			if( $this->params->get('disarts') and (in_array($id, $this->params->get('disarts'))) )
			{
				return false;	
			}
				
			if($this->params->get('discats') or $this->params->get('encats'))
			{
				// get article category
				$query = $db->getQuery(true);
				$query->select('id, catid');
				$query->from('#__content');
				$query->where('id = '.$db->quote($id));
				$db->setQuery( (string)$query );
				$cnres = $db->loadObject();
				$catid = $cnres->catid;
				
				if( $this->params->get('discats') and (in_array($catid, $this->params->get('discats'))) )
				{
					return false;
				}
				
				if( $this->params->get('encats') and (!in_array($catid, $this->params->get('encats'))) )
				{
					return false;		
				}			
			}	
		}			
		
		// Geting data needed
		$res = $this->egoltvotingModeling($article, $params);
		$ldset = $this->params->get('ldicons', 1);
		$av_val = $res->counter ? round($res->sum/$res->counter,2) : 0;
		$prog_val = round($av_val*20 ,0);
		
		// Start of output
		$output  = '<!-- Egolt Voting -->';
		$output .= '<!-- More Info: http://www.egolt.com/products/egoltvoting -->';
		
		// $output .= '<div class="egoltvoting egalign-'. $this->params->get('alignment', 'left') .'" id="egoltvoting_' . $id . '" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
		$output .= '<div class="egoltvoting egalign-left" id="egoltvoting_' . $id . '" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
		
		// Google Structured Data
		$output .= '<p class="unseen element-invisible">'
					. '<span itemprop="ratingValue">' . $av_val . '</span>'
					. '<span itemprop="bestRating">5</span>'
					. '<meta itemprop="ratingCount" content="' . (int) $res->counter . '" />'
					. '<meta itemprop="worstRating" content="0" />'
					. '</p>';

		$output .= '<div class="eglike_act" >';
		
		// Vote Buttons
		for($i=1;$i<=5;$i++)
		{
			$output .= '<div class="grid" >';
			$output .= '<div class="elike" id="eglike_'. $i .'">';
			$output .= '<a class="tooltipev evtt1"
							href="javascript:void(null)" 	
							title="<strong>'. JText::sprintf('PLG_CONTENT_EGOLTVOTING_IOFI', $i, 5) .'</strong><br/>'. JText::_('PLG_CONTENT_EGOLTVOTING_VOTE_THIS') . '" ' ;
			$output .= '	onclick="javascript:egoltvoting('.$i.', '. $id .', 1);" ';	// egoltvoting(rate, id, service)		
			$output .= '>';
			$output .= $i ;
			$output .= '</a>';
			$output .= '</div>';
			$output .= '</div>';
		}
		
		$output .= '<div class="clear"> </div>';
		$output .= '</div>';
		
		
		$output .= '<div class="clear"> </div>';
		
		// if($prog_val) {
			$output .= '<div class="rescover tooltipev evtt2" id="esumv_' . $id . '" ';
			if(!$prog_val) $output .= ' style="display:none;" ';
			// $output .= ' title="<strong>% '. $prog_val .'</strong> ['. JText::sprintf('PLG_CONTENT_EGOLTVOTING_IOFI', $av_val, 5) . ']<br/>';
			$output .= ' title="<strong>% '. $prog_val .'</strong><br/>';
			if($this->params->get('sval_show', 1))
			{
				if($res->counter == 1)
					$output .=	'<small>'.$res->counter .' '. JText::_('PLG_CONTENT_EGOLTVOTING_VOTE') .'</small>';
				else
					$output .=	'<small>'.$res->counter .' '. JText::_('PLG_CONTENT_EGOLTVOTING_VOTES') .'</small>';
			}	
			$output .= '" >';
			
			$output .= '<div class="resultvt" style="width:'. $prog_val .'%;" >';
			$output .= '</div>';
			
			$output .= '<div class="clear"> </div>';
			$output .= '</div>';
		// }
		
		// End of output
		$output .= '</div>';
				
		return $output;	
	}
	
	public function onContentBeforeDisplay($context, &$article, &$params, $limitstart = 1)
	{
		if($this->params->get('pos_show', 'beforec') == 'beforec')
		{
			return $this->egoltvotingPrepare($article, $params);
		}
 	}

	public function onContentAfterDisplay($context, &$article, &$params, $limitstart = 1)
	{
		if($this->params->get('pos_show', 'beforec') == 'afterc')
		{
			return $this->egoltvotingPrepare($article, $params);		
		}
	}	
	
}
