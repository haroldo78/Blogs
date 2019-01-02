jQuery.noConflict();
(function($) {
	 $('.menu-mobile').click(function() {        
        $(this).next(".nav").stop().slideToggle('fast');
    });
   jQuery(".showInfo").click(function(){
jQuery(this).siblings("p").toggle("fade");
}).siblings("p").hide();
        
})(jQuery);
