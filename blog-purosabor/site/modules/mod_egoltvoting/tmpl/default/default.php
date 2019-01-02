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

// Check Egolt Framework
// defined('_EGOINC') or die('Egolt Framework not installed!');
?>
<div id="eglike-<?php echo $module->id; ?>" class="eglikemod<?php echo $modsfx; ?>">
<ul>
<?php foreach($items as $item) : ?>
	<li>
		<a href="<?php echo $item->link; ?>" >
			<span><?php echo $item->title; ?></span>
			<div style="width:<?php echo $item->percent-2; ?>%;" ><?php echo $item->percent; ?> %</div>
		</a> 
	</li>
<?php endforeach; ?>
</ul>
</div>
