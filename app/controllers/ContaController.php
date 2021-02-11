<?php

use App\Forms\PwdForm;

class ContaController extends ControllerBase
{
	public function initialize()
	{
		$this->tag->setTitle('Minha conta');
		parent::initialize();
	}

	public function indexAction()
	{
		$form = new PwdForm;
		$auth = $this->session->auth;
		$this->view->auth= $auth;
		$this->view->form = $form;
	}

	public function changepwdAction()
	{
		$uid = $this->session->auth['id'];
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$user =Usuario::findFirstById($uid);
			$password = $this->request->getPost('password');
			$repeatPassword = $this->request->getPost('repeatPassword');
			if ($password == '') {
				$this->flash->error('Insira uma senha válida');
				return $this->dispatcher->forward(
					[
						"controller" => "conta",
						"action"	 => "index",
					]
				);
			}
			if ($password != $repeatPassword) {
				$this->flash->error('As senhas não conferem');
				return $this->dispatcher->forward(
					[
						"controller" => "conta",
						"action"	 => "index",
					]
				);
			}

			if (!$user) {
				$this->flash->error("Usuário inexistente.");

				return $this->dispatcher->forward(
					[
						"controller" => "conta",
						"action"	 => "index",
					]
				);
			} else {
				$user->password = sha1($password);
				if ($user->save() == false) {
					foreach ($user->getMessages() as $message) {
						$this->flash->error((string) $message);
					}
				} else {
					$this->flash->success('senha salva com sucesso.');
					return $this->dispatcher->forward(
						[
							"controller" => "conta",
							"action"	 => "index",
						]
					);
				}
			}
		} else {
			$this->flash->error('ocorreu um erro.');
			return false;
		}
	}
}
