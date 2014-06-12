/*! Login script */
jQuery.noConflict();
(function($) {
	
	$(document).ready(function(){
		
		//===== Set center position for wrapper =====//

		$('.loginWrapper').on('position', function(){
			setPosition($(this));
		})

		function setPosition(targ) {

			var tHeight	= targ.height(),
				tWidth	= targ.width();

			var calcTop		= -(tHeight/2),
				calcLeft	= -(tWidth/2);

			targ.css({
				'margin-top': calcTop,
				'margin-left': calcLeft
			});
			
			targ.animate({
				'opacity': 1
			}, 'fast');

		}
		
		$(window).load(function(){
			$('.loginWrapper').trigger('position');
		})
		
		//===== Notification boxes =====//
		
		$(".nNote").click(function() {
			$(this).animate({
				'opacity': 0
			}, 'fast', function(){
				$(this).remove();
				$('.loginWrapper').trigger('position');
			});
		});
		
		//===== Form elements styling =====//

		$("select, .check, .check:checkbox, input:radio, input:file").uniform({
			selectAutoWidth: false
		});
		
	});
	
})(jQuery);
