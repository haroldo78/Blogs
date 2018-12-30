<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();
$info    = $params->get('info_block_position', 0);
JHtml::_('behavior.caption');
?>
<?php if ($params->get('show_title') || $params->get('show_author')) : ?>
	<div class="page-header">
		<h1 itemprop="name">
			<?php if ($params->get('show_title')) : ?>
				<?php echo $this->escape($this->item->title); ?>
			<?php endif; ?>
		</h1>
		<?php if ($this->item->state == 0) : ?>
			<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
		<?php endif; ?>
		<?php if (strtotime($this->item->publish_up) > strtotime(JFactory::getDate())) : ?>
			<span class="label label-warning"><?php echo JText::_('JNOTPUBLISHEDYET'); ?></span>
		<?php endif; ?>
		<?php if ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != JFactory::getDbo()->getNullDate()) : ?>
			<span class="label label-warning"><?php echo JText::_('JEXPIRED'); ?></span>
		<?php endif; ?>
	</div>
	<?php endif; ?>
<?php if (!$params->get('show_intro')) : echo $this->item->event->afterDisplayTitle; endif; ?>
	<?php echo $this->item->event->beforeDisplayContent; ?>

	<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '0')) || ($params->get('urls_position') == '0' && empty($urls->urls_position)))
		|| (empty($urls->urls_position) && (!$params->get('urls_position')))) : ?>
	<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>
	<?php if ($params->get('access-view')):?>
	<?php if (isset($images->image_fulltext) && !empty($images->image_fulltext)) : ?>
	<?php $imgfloat = (empty($images->float_fulltext)) ? $params->get('float_fulltext') : $images->float_fulltext; ?>
	<div class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image"> <img
	<?php if ($images->image_fulltext_caption):
		echo 'class="caption"' . ' title="' . htmlspecialchars($images->image_fulltext_caption) . '"';
	endif; ?>
	src="<?php echo htmlspecialchars($images->image_fulltext); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>" itemprop="image"/> </div>
	<?php endif; ?>


<div class="centraliza">
      <div class="redes-sociais">
          <div class="curti">
        <div id="fb-root">&nbsp;</div>
        <div class="fb-like" data-href="https://www.facebook.com/papodeconsultor.com.br?fref=ts" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false">&nbsp;</div>
      </div>
      <div class="compartinha">
        <div id="fb-root">&nbsp;</div>
        <div class="fb-share-button" data-href="https://www.facebook.com/papodeconsultor.com.br?fref=ts" data-layout="button">&nbsp;</div>
      </div>
      <div class="curti-gplus">
        <!-- Posicione esta tag no cabeçalho ou imediatamente antes da tag de fechamento do corpo. -->
              <script src="https://apis.google.com/js/platform.js" async defer>
              {lang: 'pt-BR'}
             </script>

             <!-- Posicione esta tag onde você deseja que o botão +1 apareça. -->
            <div class="g-plusone" data-href="https://plus.google.com/u/0/111823116706673756800/posts"></div>
      </div>
      <div class="compartinha-gplus">
        <!-- Posicione esta tag no cabeçalho ou imediatamente antes da tag de fechamento do corpo. -->
        <!-- Posicione esta tag onde você deseja que o botão compartilhar apareça. -->
        <div class="g-plus" data-action="share" data-annotation="none" data-href="https://plus.google.com/u/0/111823116706673756800/posts">&nbsp;</div>
      </div>
      <div class="in-seguir">
            <script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: pt_BR</script>
	        <script type="IN/FollowCompany" data-id="5244607" data-counter="top"></script>
      </div>
      <div class="in-compartinha">
            <script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: pt_BR</script>
            <script type="IN/Share" data-url="https://www.linkedin.com/profile/view?id=371626125&authType=NAME_SEARCH&authToken=0_wT&locale=pt_BR&trk=tyah&trkInfo=clickedVertical%253Amynetwork%252CclickedEntityId%253A371626125%252CauthType%253ANAME_SEARCH%252Cidx%253A3-1-10%252CtarId%253A1439409408728%252Ctas%253Apapo" data-counter="right"></script>
      </div>
      </div>



	<div class="item-page<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="http://schema.org/Article">
	<meta itemprop="inLanguage" content="<?php echo ($this->item->language === '*') ? JFactory::getConfig()->get('language') : $this->item->language; ?>" />
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>
	<?php endif;
	if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative)
	{
		echo $this->item->pagination;
	}
	?>



	<?php // Todo Not that elegant would be nice to group the params ?>
	<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
	|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') ); ?>

	<?php if (!$useDefList && $this->print) : ?>
		<div id="pop-print" class="btn hidden-print">
			<?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
		</div>
		<div class="clearfix"> </div>
	<?php endif; ?>
	
	<?php if (!$this->print) : ?>
		<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
			<?php echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>
		<?php endif; ?>
	<?php else : ?>
		<?php if ($useDefList) : ?>
			<div id="pop-print" class="btn hidden-print">
				<?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
		<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
	<?php endif; ?>

	<?php if ($info == 0 && $params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
		<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>

		<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
	<?php endif; ?>

	
	<?php
	if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && !$this->item->paginationrelative):
		echo $this->item->pagination;
	endif;
	?>
	<?php if (isset ($this->item->toc)) :
		echo $this->item->toc;
	endif; ?>
	<div itemprop="articleBody">
		<?php echo $this->item->text; ?>
		<div class="redes-sociais-textos">
			<div class="curti">
		        <div id="fb-root">&nbsp;</div>
       			<div class="fb-share-button" data-href="https://www.facebook.com/papodeconsultor.com.br?fref=ts" data-layout="button">&nbsp;</div>
		     </div>
			<div class="curti-gplus">
	             <!-- Posicione esta tag no cabeçalho ou imediatamente antes da tag de fechamento do corpo. -->
	              <script src="https://apis.google.com/js/platform.js" async defer>
	              {lang: 'pt-BR'}
	             </script>

	             <!-- Posicione esta tag onde você deseja que o botão +1 apareça. -->
	             <div class="g-plus" data-action="share" data-annotation="none" data-href="https://plus.google.com/u/0/111823116706673756800/posts">&nbsp;</div>
		     </div>
			<div class="in-seguir">
		          <script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: pt_BR</script>
            <script type="IN/Share" data-url="https://www.linkedin.com/profile/view?id=371626125&authType=NAME_SEARCH&authToken=0_wT&locale=pt_BR&trk=tyah&trkInfo=clickedVertical%253Amynetwork%252CclickedEntityId%253A371626125%252CauthType%253ANAME_SEARCH%252Cidx%253A3-1-10%252CtarId%253A1439409408728%252Ctas%253Apapo" data-counter="right"></script>
		      </div>
	  	</div>
	  	<div id="fb-root"></div>
	  	<div class="comente"> Comente o Post</div>
		<div class="fb-comments" data-href="https://www.facebook.com/papodeconsultor.com.br?fref=ts" data-numposts="5">&nbsp;</div>
	</div>

	<?php if ($useDefList && ($info == 1 || $info == 2)) : ?>
		<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
	<?php endif; ?>

	<?php
	if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && !$this->item->paginationrelative):
		echo $this->item->pagination;
	?>
	<?php endif; ?>
	<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '1')) || ($params->get('urls_position') == '1'))) : ?>
	<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>
	<?php // Optional teaser intro text for guests ?>
	<?php elseif ($params->get('show_noauth') == true && $user->get('guest')) : ?>
	<?php echo $this->item->introtext; ?>
	<?php //Optional link to let them register to see the whole article. ?>
	<?php if ($params->get('show_readmore') && $this->item->fulltext != null) :
		$link1 = JRoute::_('index.php?option=com_users&view=login');
		$link = new JUri($link1);?>
	<p class="readmore">
		<a href="<?php echo $link; ?>">
		<?php $attribs = json_decode($this->item->attribs); ?>
		<?php
		if ($attribs->alternative_readmore == null) :
			echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
		elseif ($readmore = $this->item->alternative_readmore) :
			echo $readmore;
			if ($params->get('show_readmore_title', 0) != 0) :
				echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
			endif;
		elseif ($params->get('show_readmore_title', 0) == 0) :
			echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
		else :
			echo JText::_('COM_CONTENT_READ_MORE');
			echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
		endif; ?>
		</a>
	</p>
	<?php endif; ?>
	<?php endif; ?>
	<?php
	if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && $this->item->paginationrelative) :
		echo $this->item->pagination;
	?>
	<?php endif; ?>
	<?php echo $this->item->event->afterDisplayContent; ?>


	</div>
 </div>
  <?php
    jimport( 'joomla.application.module.helper' );
    $module = JModuleHelper::getModule( 'related_items', 'Posts relacionados:' );
    $attribs['style'] = 'xhtml';
    echo JModuleHelper::renderModule( $module, $attribs );

  
?>

