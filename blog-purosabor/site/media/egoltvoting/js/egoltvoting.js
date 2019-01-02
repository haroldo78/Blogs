/**
 * @package   	Egolt Voting
 * @link 		http://www.egolt.com
 * @copyright 	Copyright (C) Egolt www.egolt.com
 * @author    	Soheil Novinfard
 * @license    	GNU/GPL 2
 *
 * Name:		Egolt Voting
 * License:    	GNU/GPL 2
 * Project: 	http://www.egolt.com/products/egoltvoting
 */
 
jQuery.noConflict();
jQuery(document).ready(function(){
	
	var egvt_up = 400;
	var egvt_down = 200;
	
	jQuery('.tooltipev.evtt1').tooltipster({
		contentAsHTML: true,
	});
	
	jQuery('.tooltipev.evtt2').tooltipster({
		contentAsHTML: true,
		animation: 'grow',
		position: 'bottom',
	});
	
	
	jQuery('.elike').hover(
		function() {
			jQuery(this).find('a').stop().animate({
					backgroundColor: "#498D5B"
			}, egvt_up );
			
			var tmptmp = jQuery(this).attr('id').split('_');
			var egvt_rate = +tmptmp[1];
			var egvt_par = jQuery(this).parent().parent();
			for(i = 1; i<egvt_rate; i++) {
				egvt_par.find('#eglike_'+i+' a').stop().animate({
						backgroundColor: "#498D5B"
				}, egvt_up );
			}
		},
		function() {
			jQuery(this).find('a').stop().animate({
					backgroundColor: "#CCDCB0"
			}, egvt_down );
			var tmptmp = jQuery(this).attr('id').split('_');
			var egvt_rate = +tmptmp[1];
			var egvt_par = jQuery(this).parent().parent();
			for(i = 1; i<egvt_rate; i++) {
				egvt_par.find('#eglike_'+i+' a').stop().animate({
						backgroundColor: "#CCDCB0"
				}, egvt_down );
			}
		}		
	);
	
	jQuery('.elike').click(function(){
		jQuery(this).parent().parent().slideUp(100);
		// jQuery(this).parent().parent().parent().find('.resultvt').slideUp(100);
		jQuery(this).parent().parent().parent().find('.resultvt').remove();
		jQuery(this).parent().parent().parent().find('.rescover').show(10).animate({
			height: '22px',
			lineHeight: '22px',
			backgroundColor: "#eee"
		}, 1000 );
	});

});