<?php

use App\Forms\RetrieveForm;
use Phalcon\Di;
use Phalcon\Mvc\Model\Manager as ModelsManager;

/**
 * SessionController
 *
 * Allows to authenticate users
 */
class SessionController extends ControllerBase
{
	public function initialize()
	{
		$this->tag->setTitle('Sign Up/Sign In');
		parent::initialize();
	}
	public function indexAction()
	{
	}
	/**
	 * Register an authenticated user into session data
	 *
	 * @param Users $user
	 */
	private function _registerSession(Usuario $user,$view)
	{
		// 1 - administrador, 2 - vendedor, 3 - cliente parceiro, 4 - cliente normal
		$role = null;
		if(intval($user->usuario_tipo)==1){$role='administrador';}
		elseif(intval($user->usuario_tipo)==2){$role='vendedor';}
		elseif(intval($user->usuario_tipo)==3){$role='cliente parceiro';}
		elseif(intval($user->usuario_tipo)==4){$role='cliente normal';}
		$this->session->set('auth', array(
			'id'                             => $user->id,
			'name'                           => $user->name,
			'email'                          => $user->email,
			'vendedor'                       => $user->vendedor,
			'status'                         => $user->status,
			'client'                         => $user->codigo_cliente,
			'role'                           => $role,
			'custo_pagina'                   => $view->c_p,
			'custo_orcamento'                => $view->c_o,
			'admin_produto'                  => $view->a_p,
			'edicao_produto'                 => $view->a_ep,
			'mensagem_relacionado'           => $view->m_ur,
			'mensagem_naorelacionado'        => $view->m_unr,
			'cliente_relacionado'            => $view->c_cr,
			'cliente_naorelacionado'         => $view->c_cnr,
			'orcamento_relacionado'          => $view->o_ur,
			'orcamento_naorelacionado'       => $view->o_unr,
			'orcamento_edicaorelacionado'    => $view->o_eur,
			'orcamento_edicaonaorelacionado' => $view->o_eunr,
			'dados_empresa'                  => $view->d_me,
			'edicao_empresa'                 => $view->e_me,
			'edicao_paginas'                 => $view->e_pag,
			'pendencia_usuario'              => $view->p_u,
			'ativacao_usuario'               => $view->a_u,
			'criacao_usuario'                => $view->c_nu,
			'criacao_empresa'                => $view->c_ne,
			'edicao_cliente_contato'         => $view->a_ecc,
			'edicao_menu'                    => $view->e_menu,
			'logs'                           => $view->logs
		));
		/*
		View para cada tipo de usuário
			c_p	=	Acesso custo na página 
			c_o	=	Acesso custo no orçamento 
			a_p	=	Acesso admin produtos 
			a_ep	=	Acesso à edição de produtos 
			m_ur	=	Acesso às mensagens de usuários relacionados 
			m_unr	=	Acesso às mensagens de usuários não relacionados 
			c_cr	=	Acesso à lista de clientes relacionados 
			c_cnr	=	Acesso à lista de clientes não relacionados 
			o_ur	=	Acesso aos orçamentos de usuários relacionados 
			o_unr	=	Acesso aos orçamentos de usuários não relacionados 
			d_me	=	Acesso aos dados da minha empresa 
			e_me	=	Acesso à edição de minha empresa 
			e_pag	=	Acesso à edição de páginas 
			p_u	=	Acesso às pendências de usuários 
			a_u	=	Acesso à ativação de usuários 
			c_nu	=	Acesso à criação de novos usuários 
			c_ne	=	Acesso à criação de novas empresas 
			a_ecc	=	Acesso à edição de cliente_contato 
			e_menu	=	Acesso à edição de menus 
			logs	=	Acesso aos logs
		*/
	}
	/**
	 * This action authenticate and logs an user into the application
	 *
	 */
	public function startAction()
	{
		if ($this->request->isPost()) {

			$email = $this->request->getPost('email');
			$password = $this->request->getPost('password');
			$user = Usuario::findFirst(array(
				"email = :email:  AND password = :password: ",
				'bind' => array('email' => $email, 'password' => sha1($password))
			));
			$view = null;
			if ($user != false && ($user->status =="active" || $user->status =="confirmed")) {
				foreach ($user->UsuarioTipo->UsuarioView as $userView) {
					$view = $userView;
				}
				$this->_registerSession($user,$view);
				//$this->_updateCart();
				$this->flash->success('Bem vindo, ' . $user->name);
				if ($user->codigo_cliente == null && !in_array(intval($user->usuario_tipo), [1, 2])) {  // ADMIN does not need to have a client associated.
					$this->view->edituser = false;
					return $this->dispatcher->forward( 									
						[
							"controller" => "register",
							"action"	 => "associate",
						]
					);
				}

				return $this->response->redirect('/');
			} elseif ($user != false && $user->status =="pending") {
				$this->flash->error('Este email ainda não foi confirmado. Por favor verifique sua caixa de email.');
			// } elseif ($user != false && $user->status =="confirmed") {
			// 	$this->flash->error('Usuário inativo, por favor aguarde a ativação ou entre em contato conosco.');
			} else {
				$this->flash->error('Usuário ou senha incorretos');
			}
		}

		return $this->dispatcher->forward(
			[
				"controller" => "session",
				"action"	 => "index",
			]
		);
	}
	/**
	 * Finishes the active session redirecting to the index
	 *
	 * @return unknown
	 */
	public function endAction()
	{	
		//Apaga todos os registros do orçamento temporário
		$user_id = $this->session->auth['id'];

		$query_update = "UPDATE OrcamentoItemTemp SET open = 0 WHERE user_id = $user_id AND open=1";
		$result_query_update = $this->modelsManager->createQuery($query_update);
		$result_query_update->execute();
		
		$this->session->destroy();
		$this->flash->success('Até logo!');
		
		return $this->response->redirect('/');
	}

	public function forgotAction()
	{
		if ($this->request->isPost()) {
			$email = $this->request->getPost('email');
			if($email==''){
				$this->flash->error('Por favor insira um email válido.');
			} else {
				$session = new SessionController();
				$user = Usuario::findFirst(array(
					"email = :email: ",
					'bind' => array('email' => $email)
				));

				if ($user != false) {

					$str = "";
					$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
					$max = count($characters) - 1;
					for ($i = 0; $i < 6; $i++) {
						$rand = mt_rand(0, $max);
						$str .= $characters[$rand];
					}
					$user->confirm_code=$str;
					$user->save();

					// Email
//					$emailService = new \App\Library\Email\Usuario();
//					$response = $emailService->enviaForgotPassword($user->id);

					/*
					if ($response['status'] === 'success') {
                        $this->flash->success($response['message']);
                        return $this->response->redirect('/session/index');
                    } else {
                        $this->flash->error($response['message']);
                    }
					
					return $this->response->redirect('/session/forgot');
					*/

					$this->sendForgotPasswordEmailAction($email,$str,$user->name);
					return $this->dispatcher->forward(
						[
							"controller" => "index",
							"action"	 => "index",
						]
					);
					
				} else {
					$this->flash->error('email não existente.');
				}
			}
		}
	}	
	public function sendForgotPasswordEmailAction($email,$str,$name)
	{
		$host=$_SERVER['HTTP_HOST'];
		$to = $email;
		$subject = "GP Cabling - Reinicialização de senha.";
		$htmlContent = file_get_contents(APP_PATH ."app/views/session/forgotemail.volt");
		$htmlContent = preg_replace('[{{confirm_code}}]', $str, $htmlContent);
		$htmlContent = preg_replace('[{{email}}]', $email, $htmlContent);
		$htmlContent = preg_replace('[{{uri}}]',"https://".$host."/session" , $htmlContent);
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$mailer = $this->di['mailer'];
		$mailer->addAddress($email, $name);
		// //Set the subject line
		$mailer->Subject = 'GP Cabling - reinicie sua senha.';
		// //Read an HTML message body from an external file, convert referenced images to embedded,
		// //convert HTML into a basic plain-text alternative body
		$mailer->msgHTML($htmlContent);
		var_dump($htmlContent);
		// var_dump($mailer);
		if (!$mailer->send()) {
			$this->flash->error('Erro ao enviar o email, por favor tente novamente.');
		} else {
			$this->flash->success('Enviamos um email para o endereço informado, por gentileza, acesse sua caixa para reiniciar sua senha.');
		}
	}
	public function retrieveAction()
	{
		$form = new RetrieveForm();
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$email = $this->request->getPost('email');
			$confirm_code = $this->request->getPost('code');
			$password = $this->request->getPost('password');
			$user = Usuario::findFirst(array(
				"email = :email:  AND confirm_code = :confirm_code: ",
				'bind' => array('email' => $email, 'confirm_code' => $confirm_code)
			));
			if ($user != false) {
				$user->password = sha1($password);
				if ($user->save() == false) {
					foreach ($user->getMessages() as $message) {
						$this->flash->error((string) $message);
					}
				} else {
					$this->flash->success('senha alterada com sucesso.');
					$this->startAction();
				}
			} else{
				$this->flash->error('email ou código inexistentes.');
			}
		} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
			$email = $this->request->get('email');
			$code = $this->request->get('code');
			$this->view->email = $email;
			$this->view->code = $code;
		}
		$this->view->form = $form;
	}
	public function getPendingTasksAction()
	{
		// try{
		$tasks = [];
		if (isset($this->session->auth)) {// get user pending tasks
			$user = Usuario::query()
			->where('status="confirmed"')
			->execute();
			$userTasks=$user->count();

			/*
			$client = Cliente::query()
			->where(' usuario_atualizou="1" ')
			->execute();
			$clientsTasks=$client->count();
			*/
			$clientsTasks=0;

			$msg = ContatoFormulario::find([ "conditions" => "lida = 0","order" => "lida ASC"]);
			$msgTasks=$msg->count();
			if($this->session->auth['orcamento_naorelacionado']){
				$invoices = Orcamento::find([ "conditions" => "status = 'salvo' ","order" => "data_de_criacao DESC"]);
			} else {
				$invoices = Orcamento::find([ "conditions" => "status = 'salvo' AND usuario_id = ?1 ","bind"=>[1=>$this->session->auth['id']],"order" => "data_de_criacao DESC"]);
			}
			$invoicesTasks=$invoices->count();
			$tasks= array(
				'mensagens'  => json_encode($msgTasks), 
				'clientes'   => json_encode($clientsTasks),
				'usuarios'   => json_encode($userTasks), 
				'orcamentos' => json_encode($invoicesTasks)
				);
		}
		$this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
		$response = new \Phalcon\Http\Response();
		$response->setContent('{"ok":'.json_encode($tasks).'}');
		return $response;
		// } catch  (\Exception $e) {
		// 	echo get_class($e), ": ", $e->getMessage(), "\n";
		// 	echo " File=", $e->getFile(), "\n";
		// 	echo " Line=", $e->getLine(), "\n";
		// 	echo $e->getTraceAsString();
		// }
	}
}
