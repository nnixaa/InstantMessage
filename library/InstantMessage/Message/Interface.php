<?php

interface InstantMessage_Message_Interface
{
	
	/**
	 * Render Message
	 * @return string Rendered template
	 */
	public function render();
	
	/**
	 * Setter for message values
	 * @param array|object $values
	 * @return null
	 */
	public function setValues($values);

	/**
	 * Getter for message values
	 * @return array
	 */
	public function getValues();
	
}
