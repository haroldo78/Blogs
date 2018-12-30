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

JHtml::_('behavior.caption');
?>

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
	        <script type="IN/FollowCompany" data-id="5244607" data-counter="right"></script>
      </div>
      <div class="in-compartinha">
            <script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: pt_BR</script>
            <script type="IN/Share" data-url="https://www.linkedin.com/profile/view?id=371626125&authType=NAME_SEARCH&authToken=0_wT&locale=pt_BR&trk=tyah&trkInfo=clickedVertical%253Amynetwork%252CclickedEntityId%253A371626125%252CauthType%253ANAME_SEARCH%252Cidx%253A3-1-10%252CtarId%253A1439409408728%252Ctas%253Apapo" data-counter="right"></script>
      </div>
      </div>

<div class="blog<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="http://schema.org/Blog">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<div class="page-header">
			<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
		</div>
	<?php endif; ?>

	<?php if ($this->params->get('show_category_title', 1) or $this->params->get('page_subheading')) : ?>
		<h2> <?php echo $this->escape($this->params->get('page_subheading')); ?>
			<?php if ($this->params->get('show_category_title')) : ?>
				<span class="subheading-category"><?php echo $this->category->title; ?></span>
			<?php endif; ?>
		</h2>
	<?php endif; ?>

	<?php if ($this->params->get('show_cat_tags', 1) && !empty($this->category->tags->itemTags)) : ?>
		<?php $this->category->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
		<?php echo $this->category->tagLayout->render($this->category->tags->itemTags); ?>
	<?php endif; ?>

	<?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
		<div class="category-desc clearfix">
			<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
				<img src="<?php echo $this->category->getParams()->get('image'); ?>" alt="<?php echo htmlspecialchars($this->category->getParams()->get('image_alt')); ?>"/>
			<?php endif; ?>
			<?php if ($this->params->get('show_description') && $this->category->description) : ?>
				<?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if (empty($this->lead_items) && empty($this->link_items) && empty($this->intro_items)) : ?>
		<?php if ($this->params->get('show_no_articles', 1)) : ?>
			<p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php $leadingcount = 0; ?>
	<?php if (!empty($this->lead_items)) : ?>
		<div class="items-leading clearfix">
			<?php foreach ($this->lead_items as &$item) : ?>
				<div class="leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>"
					itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting">
					<?php
					$this->item = & $item;
					echo $this->loadTemplate('item');
					?>
				</div>
				<?php $leadingcount++; ?>
			<?php endforeach; ?>
		</div><!-- end items-leading -->
	<?php endif; ?>

	<?php
	$introcount = (count($this->intro_items));
	$counter = 0;
	?>

	<?php if (!empty($this->intro_items)) : ?>
		<?php foreach ($this->intro_items as $key => &$item) : ?>
			<?php $rowcount = ((int) $key % (int) $this->columns) + 1; ?>
			<?php if ($rowcount == 1) : ?>
				<?php $row = $counter / $this->columns; ?>
				<div class="items-row cols-<?php echo (int) $this->columns; ?> <?php echo 'row-' . $row; ?> row-fluid clearfix">
			<?php endif; ?>
			<div class="span<?php echo round((12 / $this->columns)); ?>">
				<div class="item column-<?php echo $rowcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>"
					itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting">
					<?php
					$this->item = & $item;
					echo $this->loadTemplate('item');
					?>
				</div>
				<!-- end item -->
				<?php $counter++; ?>
			</div><!-- end span -->
			<?php if (($rowcount == $this->columns) or ($counter == $introcount)) : ?>
				</div><!-- end row -->
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php if (!empty($this->link_items)) : ?>
		<div class="items-more">
			<?php echo $this->loadTemplate('links'); ?>
		</div>
	<?php endif; ?>

	<?php if (!empty($this->children[$this->category->id]) && $this->maxLevel != 0) : ?>
		<div class="cat-children">
			<?php if ($this->params->get('show_category_heading_title_text', 1) == 1) : ?>
				<h3> <?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?> </h3>
			<?php endif; ?>
			<?php echo $this->loadTemplate('children'); ?> </div>
	<?php endif; ?>
	<?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
		<div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?> </div>
	<?php endif; ?>
</div>
