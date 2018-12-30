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

			
    		// Para deixa o Header position fixed utilizando uma
    		// div como referencia que esta na index
		    $(window).scroll(function () {
		    	var height = $(".refTop").position().top;
		    	var top = $(window).scrollTop();
		    	console.log(top);

		        if (top > height) {
		            $("header").addClass("lock");
		        }
		        if(top <= height){
		            $("header").removeClass("lock");
		        }
		        
		    });
		    
		});	


})(jQuery);



