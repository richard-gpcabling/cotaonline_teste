<?php

use Phalcon\Mvc\Model\Criteria;
// use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Paginator\Adapter\NativeArray as Paginator;

class CotaonlineController extends ControllerBase
{
	public function initialize()
	{
		$this->tag->setTitle('Home');
		parent::initialize();


		if (!isset($this->session->cart)) {
			$this->session->set('cart', array());
		}
	}

	public function indexAction()
	{
		return $this->response->redirect('/');
	}
}
