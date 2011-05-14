<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initApp()
	{
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('InstantMessage_');
		
		
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		$viewRenderer->initView();
		$viewRenderer->view->addHelperPath('InstantMessage/Helper/', 'InstantMessage_Helper');
	}
}

