<?php
/**
 * SessionController
 *
 * Allows to register new users
 */

// use Throwable;
use App\Forms\RegisterForm;
use App\Services\ReCaptchaService;

class RegisterController extends ControllerBase
{
	/**
	 * @var ReCaptchaService
	 */
	protected $reCaptchaService;

	public function initialize()
	{
		$this->tag->setTitle('Registro/Login');
		parent::initialize();
		$this->reCaptchaService = new ReCaptchaService($this->config);
	}

	/**
	 * Action to register a new user
	 */
	public function indexAction()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$name = $this->request->getPost('name', array('string', 'striptags'));
			$email = $this->request->getPost('email', 'email');
			$password = $this->request->getPost('password');
			$repeatPassword = $this->request->getPost('repeatPassword');

			if ($password != $repeatPassword) {
				$this->flash->error('As senhas não conferem');
			}

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$this->flash->error('E-mail inválido');
			}

			try {
				$this->reCaptchaService->verifyRequest($this->request);
			} catch (Throwable $th) {
				$this->flash->error($th->getMessage());
			}

			if (empty($this->flash->getMessages("error", false))) {
				$str = "";
				$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
				$max = count($characters) - 1;
				for ($i = 0; $i < 6; $i++) {
					$rand = mt_rand(0, $max);
					$str .= $characters[$rand];
				}
				$register = new RegisterController();
				$user = new Usuario();
				$user->cliente_tipo = 1;
				$user->usuario_tipo = 4;
				$user->username = $email;
				$user->password = sha1($password);
				$user->name = $name;
				$user->email = $email;
				$user->created_at = new Phalcon\Db\RawValue('now()');
				$user->status = 'pending';
				$user->confirm_code=$str;
				if ($user->save() == false) {
					foreach ($user->getMessages() as $message) {
						$this->flash->error((string) $message);
					}
				} else {
					$register->sendConfirmationEmailAction($email,$str,$name);
					$this->tag->setDefault('email', '');
					$this->tag->setDefault('password', '');
					$this->flash->success('Obrigado por se registrar. Enviamos um código por email. Por gentileza, acesse sua caixa de email e confirme-o.');

					return $this->dispatcher->forward(
						[
							"controller" => "session",
							"action"	 => "index",
						]
					);
				}
			}
		} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
			if(isset($_REQUEST["action"]) && $_REQUEST['action']=="confirm"){
				$email=$_REQUEST['email'];
				$code=$_REQUEST['code'];
				$user = Usuario::findFirst(array(
				"email = :email:  AND confirm_code = :code: ",
				'bind' => array('email' => $email, 'code' => $code)
				));

				if ($user != false AND $user->status=="active" OR $user != false AND $user->status=="confirmed"){
					$this->flash->success('Seu e-mail já está confirmado, já pode fazer seu login.');
					return $this->dispatcher->forward(
						[
							"controller" => "session",
							"action"	 => "index",
						]
					);
				}

				if ($user != false AND $user->status=="pending") {
					$user->status="confirmed";
					$user->save();
					$this->flash->success('Email confirmado, obrigado por se registrar. Falta apenas um passo, efetue o login e associe sua empresa.');
					return $this->dispatcher->forward(
						[
							"controller" => "session",
							"action"	 => "index",
						]
					);
				} else {

				$this->flash->error('Desculpe-nos, aconteceu algum erro. Por favor, entre em contato conosco.');
				}
			}
		}

		$this->view->form = new RegisterForm;
	}

	public function sendConfirmationEmailAction($email,$str,$name)
	{
		$host=$_SERVER['HTTP_HOST'];
		$p = ($host == 'gpcabling.com.br' or $host == 'www.gpcabling.com.br')?'https://':'http://';
		$htmlContent = file_get_contents(APP_PATH ."app/views/register/email.volt");
		$htmlContent = preg_replace('[{{confirm_code}}]', $str, $htmlContent);
		$htmlContent = preg_replace('[{{email}}]', $email, $htmlContent);
		$htmlContent = preg_replace('[{{uri}}]',$p.$host."/register" , $htmlContent);
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$mailer = $this->di['mailer'];
		$mailer->addAddress($email, $name);
		// //Set the subject line
		$mailer->Subject = 'GP Cabling - Por favor confirme seu email.';
		// //Read an HTML message body from an external file, convert referenced images to embedded,
		// //convert HTML into a basic plain-text alternative body
		$mailer->msgHTML($htmlContent);

		$mailer->send();
	}

	public function AssociateAction($userId=null,$action=null)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$clientId = $this->request->getPost('id');
			if($clientId){
				if(($this->session->auth['status']=='active' || $this->session->auth['status']=='confirmed') && $this->session->auth['id']){  //All set, go for it
					if ($action!="change") { // user associating client to his/her account
						$userId  = $this->session->auth['id'];
						$edituser = false;
					} else {
						$edituser = true;
					}
					$this->view->edituser = $edituser;
					$usuario = Usuario::findFirstById($userId);
					if (!$usuario) {
						$this->flash->error("usuario inexistente.");

						return $this->dispatcher->forward(
							[
								"controller" => "session",
								"action"	 => "index",
							]
						);
					}
					if ($this->session->auth['role']!='administrador' && $this->session->auth['id'] != $usuario->vendedor && $action=="change") {
						return $this->dispatcher->forward(
							[
								"controller" => "errors",
								"action"	 => "401",
							]
						);
					}
					$cliente = ClienteCore::findFirstById($clientId);
					if (!$cliente) {
						$this->flash->error("cliente inexistente.");

						return $this->dispatcher->forward(
							[
								"controller" => "session",
								"action"	 => "index",
							]
						);
					}
					$usuario->codigo_cliente = $cliente->clienteUcode;
					$usuario->_runValidator=false;
					if ($usuario->save() == false) {
						foreach ($usuario->getMessages() as $message) {
							$this->flash->error($message);
						}

						return $this->dispatcher->forward(
							[
								"controller" => "register",
								"action"	 => "associate",
								"params"	 => [$id] // TODO: Fix this. $id is not defined.
							]
						);
					}
				} else { // Bad robot, something wrong with user session
						return $this->dispatcher->forward(array(
						'controller' => 'session',
						'action'	 => 'index',
					));
					$this->session->destroy();
					return false;
				}
				if ($action=="change"){
					$this->flash->success('Usuário atualizado com sucesso');
					 $this->response->redirect("usuario/index");
				}else{
					return $this->dispatcher->forward(
						[
							"controller" => "register",
							"action"	 => "updateClient",
						]
					);
				}
			}

		} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
		}
	}

	public function UpdateClientAction()
	{
		if($this->session->auth['id']){
			$usuario = Usuario::findFirstById($this->session->auth['id']);
			if (!$usuario) {
				$this->flash->error("usuario inexistente.");

				return $this->dispatcher->forward(
					[
						"controller" => "session",
						"action"	 => "index",
					]
				);
			}
			$this->_registerSession($usuario,$view); //TODO: Fix this. $view is not defined.
		}
	}

	private function _registerSession(usuario $user,$view)
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

	public function cnpjrawAction(){ //TODO: Fix this. function is empty.
	}
}
