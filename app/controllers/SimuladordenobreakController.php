<?php

use Phalcon\Mvc\Model\Criteria;
// use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Paginator\Adapter\NativeArray as Paginator;

class SimuladordenobreakController extends ControllerBase
{
	public function initialize()
	{
		parent::initialize();
	}

	public function indexAction()
	{
		$this->response->redirect('http://www.simuladordenobreak.com.br/');
	}
}
