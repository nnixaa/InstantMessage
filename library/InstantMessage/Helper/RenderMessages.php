<?php

class InstantMessage_Helper_RenderMessages extends Zend_View_Helper_Abstract
{
	
	/**
	 * Renders messages
	 * @param array $messages
	 * @return string
	 */
	public function renderMessages(array $messages)
	{
		$messagesBroker = InstantMessage_Broker::getInstance();
		$messagesBroker->setMessages($messages);
		return $messagesBroker->render();
	}
}

