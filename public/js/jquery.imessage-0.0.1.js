/**
 * @author d.nehaychik
 */

(function($){
	$.fn.imessage = function(options) {
		
		var settings = {
			'timeout':					60,
			'url':						null,
			'onMessageHover':			null,
			'onMessageClick':			null,
			'onRequest':				null,
			'onResponse':				null,
			'messageWrapper':			'<div>',
			'messageClass':				'imessage-message',
			'messageNewClass':			'imessage-message-new',
			'messageUnreadClass':		'imessage-message-unread',
			'removeNewClassTimeout':	6
		};
		
		var containers = {}
		
		$.extend(settings, options);
		
		this.each(function() {     
			var $this = $(this);
				
			var interval = setInterval(function() {
				
				$.post(settings.url, function(data) {

					if (data != undefined && data != null) {
						
						if (settings.onResponse != null && settings.onResponse instanceof Function) {
							
							settings.onResponse.call(this, data);
						}

						for (key in data) {
							var message = $(settings.messageWrapper).addClass(settings.messageClass).html(data[key])
								.addClass(settings.messageNewClass).addClass(settings.messageUnreadClass);
							
							// messageNewClass removing after newTimeout secconds
							setTimeout(function() {message.removeClass(settings.messageNewClass)}, settings.removeNewClassTimeout * 1000);
							
							addEvent(message, settings, 'onMessageHover', 'mouseover');
							addEvent(message, settings, 'onMessageClick', 'click');
							
							$this.prepend(message);
						}
					}
					
				}, 'json');
				
			}, settings.timeout * 1000);
			
			addEvent($this, settings, 'onRequest', 'ajaxStart');
			
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