jQuery.noConflict();
	(function($) {

		$(document).ready(function(){
			// Trazendo um menu mobile com a class animate
			$('.menu-mobile').click(function(){
				$(this).toggleClass('close');
				$("#menu").stop().animate({
				    width: "toggle",
				  }, 400, function() {
				    // Animation complete.
				  });
			});
			// Add Class Formulários
		    $('input').focus(function(){
		      $(this).addClass("parcial")
		    });
		    $('input').keypress(function(){
		      $(this).addClass("full")
		    });
		    $('input').blur(function(){    
		      if ( $(this).val() === "" || $(this).val() === " " || $(this).val() === "  " )
		        {
		            $(this).removeClass("parcial");
		            $(this).removeClass("full")
		        }
		    });

		});	


})(jQuery);