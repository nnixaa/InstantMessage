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
		$template = 'hello';
		$messages = InstantMessage_Broker::getInstance();
		foreach ($substitution as $values)
		{
			$messages->addMessage($template, $values, 'FileMessage', array('extension' => '.phtml'));
		}
		
		$this->view->messageOutput = $messages->render();
    }
	
	/**
	 * Base usage
	 */
	public function baseusageAction()
	{
		$message = new InstantMessage_Message_FileMessage('hello', array('name' => 'Dmitry'));
		
		$message->text = 'Hello world!';
		$message->date = date('Y-m-d', time());
		
		
		// or echo $message;
		echo $message->render();
		
		die();
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
			$this->view->tweets = $messages->render();
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
}
