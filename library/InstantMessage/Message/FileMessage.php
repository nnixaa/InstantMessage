<?php

class InstantMessage_Message_FileMessage extends InstantMessage_Message_Abstract implements InstantMessage_Message_Interface
{
	
	/**
	 * Template file extension
	 * @var string
	 */
	protected $_extension = '.phtml';
	
	/**
	 * Template directory
	 * @var string
	 */
	protected $_directory = 'imessages';
	
	/**
	 * Base tempalte path
	 * @var string
	 */
	protected $_basePath = '';

	/**
	 * Template name
	 * @var string
	 */
	protected $_template = '';
	
	/**
	 * Template file
	 * @var string
	 */
	protected $_file = '';
	
	
	/**
	 * @param string $template
	 * @param array|object $values
	 */
	public function __construct($template, $values = null, $options = array())
	{
		$this->setTemplate($template)
			->setValues($values)
			->setBasepath()
			->setOptions($options);
	}
	
	/**
	 * Render message template
	 * @return string
	 */
	public function render()
	{
		// get file name
		$file = $this->prepareFilePath();

		if (!is_readable($file))
		{
			throw new InstantMessage_Exception('Can\'t open template: ' . $file);
		}
		
		return $this->run($file);
	}

	/**
	 * Render template file
	 * @param string $file
	 */
	public function run($file)
	{
		$this->_file = $file;
		// remove $file from local scope
		unset($file);
		
		ob_start();
		
		include $this->_file;
		
		return ob_get_clean();
	}
	
	/**
	 * Returns file name
	 * @return string
	 */
	public function prepareFilePath()
	{
		return $this->_basePath . DIRECTORY_SEPARATOR . $this->_directory . DIRECTORY_SEPARATOR
			. $this->_template  . $this->_extension;
	}
	
	/**
	 * Sets template name
	 * @param sttrin $template
	 * @return InstantMessage_Message_FileMessage
	 */
	public function setTemplate($template)
	{
		$this->_template = $template;
		return $this;
	}
	
	/**
	 * Sets template directory
	 * @param string $directory
	 * @return InstantMessage_Message_FileMessage
	 */
	public function setDirectory($directory)
	{
		$this->_directory = $directory;
		return $this;
	}

	/**
	 * Sets template file extension
	 * @param string $extension
	 * @return InstantMessage_Message_FileMessage
	 */
	public function setExtenstion($extension)
	{
		$this->_extension = $extension;
		return $this;
	}
	
	/**
	 * Sets base path
	 * @param string|null $path
	 * @return InstantMessage_Message_FileMessage
	 */
	public function setBasepath($path = null)
	{
		if (null == $path)
		{
			$this->_basePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'views/scripts';
		}
		else
		{
			$this->_basePath = $path;
		}
		
		return $this;
	}
	
	/**
	 * Sets options array
	 * @param array $options
	 * @return InstantMessage_Message_FileMessage
	 */
	public function setOptions(array $options)
	{
		foreach ($options as $name => $value)
		{
			switch ($name)
			{
				case 'extension':
					$this->setExtenstion($value);
					break;
					
				case 'directory':
					$this->setDirectory($value);
					break;
					
				case 'basePath':
					$this->setBasepath($value);
					break;
			}
		}
		return $this;
	}
	
}
