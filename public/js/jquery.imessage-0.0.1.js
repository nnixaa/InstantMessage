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
			'onBeforeInsert':	null,
			'messageWrapper':	'<div>',
			'messageClass':		'imessage-item'
		};
		
		
		this.each(function() {
			
			$.extend(settings, options);
			
			var $this = $(this);
				
				
			if (!settings.url) {
				 $.error('Url is not specified');
			} else {
					
				setInterval(function() {
					$.post(settings.url, function(data) {
	
						callEvent(settings, 'onResponse', $this, data);
							
						if (data != undefined && $(data['messages']).length > 0) {
	
							$(data['messages']).each(function(number, element) {
								
								if (settings.messageWrapper != null) {
									var message = $(settings.messageWrapper).html(element);
								} else {
									var message = $(element);
								}
								
								message.addClass(settings.messageClass);
								
								addEvent(message, settings, 'onMessageHover', 'mouseover');
								addEvent(message, settings, 'onMessageClick', 'click');
								
								callEvent(settings, 'onBeforeInsert', $this, message);
							
								$this.prepend(message);
							});
						}
						
					}, 'json');
					
				}, settings.timeout * 1000);
				
				addEvent($this, settings, 'onRequest', 'ajaxStart');
			}
			
		});
		
		
	};
	
	// Custom helpers
	
	/**
	 * Sets element event
	 */
	function addEvent(element, settings, customEvent, event) {
		if (settings[customEvent] != null && settings[customEvent] instanceof Function) {
			element.bind(event, settings[customEvent]);
		}
	}
	
	/**
	 * Calls custom element event
	 */
	function callEvent(settings, event, _this, data) {

		if (settings[event] != null && settings[event] instanceof Function) {
			settings[event].call(_this, data);
		}
	}

})(jQuery);