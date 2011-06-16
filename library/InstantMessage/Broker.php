<?php

require_once 'InstantMessage/Message/Interface.php';

class InstantMessage_Broker
{
	
	/**
	 * Singleton instance
	 * @var InstantMessage_Broker
	 */
	protected static $_instance = null;
	
	/**
	 * Messages
	 * @var array
	 */
	protected $_messages = array();
	
	/**
	 * Singleton can't be constructed or cloned
	 */
	protected function __construct() {}
	
	/**
	 * Singleton can't be constructed or cloned
	 */
	protected function __clone() {}
	
	/**
     * Retrieve singleton instance
     *
     * @return InstantMessage_Broker
     */
    public static function getInstance()
    {
        if (null === self::$_instance) 
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	
	/**
	 * Renders all messages
	 * @return string
	 */
	public function render()
	{
		$output = '';
		foreach ($this->_messages as $message)
		{
			$output .= $message->render();
		}
		
		return $output;
	}
	
	/**
	 * Creates new message and sets it to messages array
	 * @param string $template 
	 * @param array|object $values
	 * @param string $type
	 * @param array $options Message options
	 * @return int Index
	 */
	public function addMessage($template, $values = null, $type = 'FileMessage', $options = array())
	{
		$mesageClass = 'InstantMessage_Message_' . $type;
		require_once 'InstantMessage/Message/' . $type . '.php';
		
		$message = new $mesageClass($template, $values, $options);
		
		if (!$message instanceof InstantMessage_Message_Interface)
		{
			require_once 'InstantMessage/Exception.php';
			throw new InstantMessage_Exception('Message should be instance of InstantMessage_Message_Interface');
		}
		
		return $this->setMessage($message);
	}
	
	/**
	 * Sets message to array
	 * @param InstantMessage_Message_Interface $message
	 */
	public function setMessage(InstantMessage_Message_Interface $message)
	{
		$this->_messages[] = $message;
		return count($this->_messages) - 1;
	}

	/**
	 * Sets messages
	 * @param array $messages
	 */
	public function setMessages(array $messages)
	{
		$this->_messages = $messages;
	}
	
	/**
	 * Gets messages
	 * @return array
	 */
	public function getMessages()
	{
		return $this->_messages;
	}
	
	/**
	 * Removes message
	 * @param int|InstantMessage_Message_Interface $index
	 */
	public function removeMessage($index)
	{
		if ($index instanceof InstantMessage_Message_Interface)
		{
			$this->removeMessageByObject($index);
		}
		elseif (isset($this->_messages[$index]))
		{
			unset($this->_messages[$index]);
		}
	}
	
	/**
	 * Removes message
   * TODO: maybe should return bolean?
	 * @param $message InstantMessage_Message_Interface $index
	 */
	public function removeMessageByObject(InstantMessage_Message_Interface $object)
	{
		foreach ($this->_messages as $key => $message)
		{
			if (spl_object_hash($object) == spl_object_hash($message))
			{
				unset($this->_messages[$key]);
        break;
			}
		}
	}
}
