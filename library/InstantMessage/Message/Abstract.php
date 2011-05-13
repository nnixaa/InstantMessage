<?php

abstract class InstantMessage_Message_Abstract
{
	
	/**
	 * Template variables
	 * @var array
	 */
	protected $_values = array();
	
	/**
	 * Setter for the template variable
	 * @param array|object $values
	 * @return InstantMessage_Message_Abstract
	 */
	public function setValues($values)
	{
		$values = (array) $values;
		foreach ($values as $key => $value)
		{
			$this->_values[$key] = $value;
		}
		return $this;
	}

	/**
	 * Getter for the template variable
	 * @param string $name
	 * @return array
	 */
	public function getValues($key = null)
	{
		if (null == $key)
		{
			return $this->_values;
		}
		
		return $this->_values[$key];
	}
	
	/**
	 * Assigns a variable to the template
	 * @param string $key
	 * @param string $value
	 */
	public function __set($key, $value)
	{
		$this->_values[$key] = $value;
	}

	/**
	 * Returns a template variable
	 * @return string
	 */
	public function __get($key)
	{
		return isset($this->_values[$key]) ? $this->_values[$key] : null;
	}
	
	public function __toString()
	{
		return $this->render();
	}
}
