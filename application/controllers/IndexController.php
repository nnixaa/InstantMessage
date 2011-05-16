<?php

class IndexController extends Zend_Controller_Action
{

    public function indexAction()
    {
    	$substitution = array(
			array('name' => 'Alex', 'date' => date('d-m-Y', strtotime('now')), 'text' => 'Test message from user 1'),
			array('name' => 'Dmitry', 'date' => date('d-m-Y', strtotime('one hour ago')), 'text' => 'Test message from user 2'),
			array('name' => 'Jack', 'date' => date('d-m-Y', strtotime('two hour ago')), 'text' => 'Hello world'),
		);

		$messages = InstantMessage_Broker::getInstance();
		foreach ($substitution as $values)
		{
			$messages->addMessage('hello', $values, 'FileMessage', array('extension' => '.phtml'));
		}
		
		$this->view->messages = $messages->getMessages();
    }
	
	/**
	 * Base usage
	 */
	public function baseusageAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		
		$message = new InstantMessage_Message_FileMessage('hello', array('name' => 'Dmitry'));
		
		$message->text = 'Hello world!';
		$message->date = date('Y-m-d', time());
		
		
		// or echo $message;
		echo $message->render();
	}

	public function twitterAction()
	{
		$lastTweet = new Zend_Session_Namespace('tweet');
		
		$messages = InstantMessage_Broker::getInstance();
		
		$tweets = @simplexml_load_file('http://api.twitter.com/1/statuses/public_timeline.xml');

		if ($tweets)
		{
			foreach ($tweets->status as $tweet)
			{
				$values = new stdClass();
				
				$values->name = $tweet->user->screen_name;
				$values->time = date('d-m-Y', strtotime($tweet->created_at));
				$values->text = $tweet->text;
	
				$messages->addMessage('tweet', $values);
			}
	
			$this->view->count = count($tweets->status);
			$this->view->tweets = $messages->getMessages();
		} 
		else
		{
			$this->view->count = 0;
			$this->view->tweets = array();
		}
	}
	
	public function ajaxtweetAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		
		$messages = InstantMessage_Broker::getInstance();
		
		$tweets = @simplexml_load_file('http://api.twitter.com/1/statuses/public_timeline.xml');
		if ($tweets)
		{
			$tweet = isset($tweets->status[0]) ? $tweets->status[0] : false;
			if ($tweet)
			{
				$message = new InstantMessage_Message_FileMessage('tweet');
				$message->name = $tweet->user->screen_name;
				$message->time = date('d-m-Y', strtotime($tweet->created_at));
				$message->text = $tweet->text;
				
				// or echo $message;
				echo $message->render();
			}
		}
	}
	
	public function removeAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		
		$messagesBroker = InstantMessage_Broker::getInstance();
		
		//create message
		$message = new InstantMessage_Message_FileMessage('hello');
		$message->name = 'Bill';
		$message->time = date('d-m-Y', strtotime('now'));
		$message->text = 'Some text';
		
		$messagesBroker->setMessage($message);
		
		echo 'Before remove:';
		echo '<pre>';
		var_dump($messagesBroker->getMessages());
		echo '</pre>';
		
		// remove message by object
		$messagesBroker->removeMessage($message);
		
		echo 'After remove:';
		echo '<pre>';
		var_dump($messagesBroker->getMessages());
		echo '</pre>';
		
	}
	
	public function ajaximessageAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		
		$messages = array(
			'<strong>Jack</strong><br><i>hello world!</i>',
			'<strong>Bill</strong><br><i>text text</i>',
			'<strong>John</strong><br><i>message from John</i>',
			'<strong>Ted</strong><br><i>hi!</i>',
		);
		

		echo $this->_helper->json(array($messages[array_rand($messages)]), true);
	}
}

