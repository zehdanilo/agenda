/* =========================================================
 * Bootstrap Alert
 * Created by Plecyo Nahay
 * HOW TO USE: 
 * 
 * $(element).mensagem('success', 'My Message')
 * $(element).mensagem('danger' , 'My Message', 'My Title')
 * $(element).mensagem('info'   , 'My Message', 'My Title', 10)
 * $(element).mensagem('warning', 'My Message', 'My Title', 10, false)
 * 
 * var type 	   = (danger OR success OR info OR warning) BOOTSTRAP
 * var title 	   = (any String OR NULL)
 * var message 	   = (any String)
 * var dismissable = True OR False
 * var autoclose   = (any value int) in seconds
 *
 * ========================================================= */
 
(function($){
    $.fn.extend({
        mensagem: function(type, title, message, dismissable, autoclose){
			var $element = $(this);
			
			var $div = $('<div class="alert alert-dismissable fade in"></div>');
				$div.addClass('alert-' + type);
				
            if(title != null && title !== ''){
                $div.append('<h4>' + title + '</h4><hr class=\"margin-top-0 margin-bottom-5\">');
            }
            
            $div.append(message);
            
            if(typeof dismissable === 'undefined' || dismissable === true){
            	$div.prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
            }
				
			if(typeof autoclose === "number"){
				$div.delay(autoclose*1000)
					.slideUp(300)
					.queue(function(next) {
						$(this).alert('close');
					});
			}
			
			$element.html($div);
			$('html, body').animate({ scrollTop: $(this).offset().top-10 }, 'fast');
        }
    });
})(jQuery);