/**
 * @author d.nehaychik
 */

(function($){
	$.fn.imessage = function(options) {
		
		var settings = {
			'timeout':			60,
			'url':				null,
			'onMessageHover':	null,
			'onMessageClick':	null,
			'onRequest':		null,
			'onResponse':		null,
			'messageWrapper':	'<div>',
			'messageClass':		'imessage-message'
		};
		
		var containers = {}
		
		$.extend(settings, options);
		
		this.each(function() {     
			var $this = $(this);
				
			var interval = setInterval(function() {
				
				$.post(settings.url, function(data) {
					
					if (data != undefined && data != null) {
						
						for (key in data) {
							var message = $(settings.messageWrapper).addClass(settings.messageClass).html(data[key]);
							
							addEvent(message, settings, 'onMessageHover', 'mouseover');
							addEvent(message, settings, 'onMessageClick', 'click');
							
							$this.prepend(message);
						}
					}
					
				}, 'json');
				
			}, settings.timeout * 1000);
			
			addEvent($this, settings, 'onRequest', 'ajaxStart');
			addEvent($this, settings, 'onResponse', 'ajaxSuccess');
			
		});
		
		
	};
	
	// Custom helpers
	
	function addEvent(element, settings, customEvent, event)
	{
		if (settings[customEvent] != null && settings[customEvent] instanceof Function) {
			element.bind(event, settings[customEvent]);
		}
	}
	
})(jQuery);