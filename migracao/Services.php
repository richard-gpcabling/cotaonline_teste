<?php

use Phalcon\Mvc\View;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaData;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Events\Manager as EventsManager;

class Services extends \Base\Services
{

	/**
	 * We register the events manager
	 */
	protected function initDispatcher()
	{
		$eventsManager = new EventsManager;

		/**
		 * Check if the user is allowed to access certain action using the SecurityPlugin
		 */
		$eventsManager->attach('dispatch:beforeDispatch', new SecurityPlugin);

		/**
		 * Handle exceptions and not-found exceptions using NotFoundPlugin
		 */
		$eventsManager->attach('dispatch:beforeException', new NotFoundPlugin);

		$dispatcher = new Dispatcher;
		$dispatcher->setEventsManager($eventsManager);

		return $dispatcher;
	}

	/**
	 * The URL component is used to generate all kind of urls in the application
	 */
	
	protected function initUrl()
	{
		$url = new UrlProvider();
		$url->setBaseUri($this->get('config')->application->baseUri);
		return $url;
	}

	protected function initView()
	{
		$view = new View();

		$view->setViewsDir(APP_PATH . $this->get('config')->application->viewsDir);

		$view->registerEngines(array(
			".volt" => 'volt'
		));

		return $view;
	}

	/**
	 * Setting up volt
	 */
	protected function initSharedVolt($view, $di)
	{
		$volt = new VoltEngine($view, $di);

		$volt->setOptions(
			[
			'compileAlways'=>true
			]
			);

		// $volt->setOptions(array(
		//	 "compiledPath" => APP_PATH . "cache/volt/"
		// ));

		$compiler = $volt->getCompiler();
		$compiler->addFunction('is_a', 'is_a');

		return $volt;
	}

	/**
	 * Database connection is created based in the parameters defined in the configuration file
	 */
	protected function initDb()
	{
		$config = $this->get('config')->get('database')->toArray();

		$dbClass = 'Phalcon\Db\Adapter\Pdo\\' . $config['adapter'];
		unset($config['adapter']);

		return new $dbClass($config);
	}

	/**
	 * If the configuration specify the use of metadata adapter use it or use memory otherwise
	 */
	protected function initModelsMetadata()
	{
		return new MetaData();
	}

	/**
	 * Start the session the first time some component request the session service
	 */
	protected function initSession()
	{
		$session = new SessionAdapter();
		$session->start();
		return $session;
	}

	/**
	 * Register the flash service with custom CSS classes
	 */
	protected function initFlash()
	{
		$commonClasses = ' flash-message ';
		return new FlashSession(array(
			'error'   => 'alert alert-danger '  . $commonClasses,
			'success' => 'alert alert-success ' . $commonClasses,
			'notice'  => 'alert alert-info '    . $commonClasses,
			'warning' => 'alert alert-warning ' . $commonClasses
		));
	}

	/**
	 * Register a user component
	 */
	protected function initElements()
	{
		return new Elements();
	}
	protected function initMailer()
	{
		$appDir = $this->get('config')->get('application')->toArray();
		require '../'.$appDir['mailDir'].'/PHPMailerAutoload.php';
		$config = $this->get('config')->get('mail')->toArray();
		//Create a new PHPMailer instance
		$mailer = new PHPMailer;
		//Tell PHPMailer to use SMTP
		$mailer->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mailer->SMTPDebug = 0;
		//Ask for HTML-friendly debug output
		$mailer->Debugoutput = 'html';
		//Set the hostname of the mail server
		$mailer->Host = $config['Host'];
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mailer->Port = $config['Port'];
		//Whether to use SMTP authentication
		$mailer->SMTPAuth = $config['SMTPAuth'];
		//Username to use for SMTP authentication
		$mailer->Username = $config['Username'];
		//Password to use for SMTP authentication
		$mailer->Password = $config['Password'];
		//Set who the message is to be sent from
		$mailer->setFrom($config['Username'], 'Contato');
		$mailer->SMTPOptions = array(
		   'ssl' => array(
			   'verify_peer' => false,
			   'verify_peer_name' => false,
			   'allow_self_signed' => true
			)
		);

		// var_dump($mailer);
		return $mailer;
		
	}

}