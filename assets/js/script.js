(function($,sr){

  // debouncing function from John Hann
  // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
  var debounce = function (func, threshold, execAsap) {
      var timeout;

      return function debounced () {
          var obj = this, args = arguments;
          function delayed () {
              if (!execAsap)
                  func.apply(obj, args);
              timeout = null;
          };

          if (timeout)
              clearTimeout(timeout);
          else if (execAsap)
              func.apply(obj, args);

          timeout = setTimeout(delayed, threshold || 100);
      };
  }
  // smartresize 
  jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');
jQuery.noConflict();

(function($){
	$.cashier = function(options) {
		var defaults = {
			jsonProductUrl : '',
			jsonPersonUrl : ''
		};
		var settings = $.extend({}, defaults, options);
		
		var jsonProductUrl	= settings.jsonProductUrl,
			jsonPersonUrl	= settings.jsonPersonUrl;
		
		/*
		* initialise function
		*/
		var _init = function(){
			_autoComplete();
			_layout();
		}
		/*
		* autoComplete function
		*/
		var _autoComplete = function(){
			//alert(jsonProductUrl + ' ' + jsonPersonUrl);
		}
		/*
		* layout function
		*/
		var _layout = function(){
			var primaryHeight;
			$(window).on('resize',function(){
				
				primaryHeight = $(window).height() - parseInt($('.content').css('padding-top'));
					
				if (primaryHeight <= $('#sidebar').outerHeight(true)) primaryHeight = $('#sidebar').outerHeight(true);
				$('.content').css('min-height', primaryHeight);
			});
			$(window).trigger('resize');
		}
		return _init();
	};

})(jQuery);
