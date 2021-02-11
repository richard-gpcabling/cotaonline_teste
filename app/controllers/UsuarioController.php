<?php

use App\Forms\UsuarioForm;
use App\Services\UsuarioService;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Manager;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Paginator\Adapter\Model as Paginator;


class UsuarioController extends ControllerBase
{
	public function initialize()
	{
		$this->tag->setTitle('Usuários');
		parent::initialize();
	}

	public function indexAction()
	{
		$users = $this->searchAction();
		// $users = Usuario::getUsers(1000);

		$count_user_tot			=	Usuario::count();
		$count_user_sem_emp		=	Usuario::count("codigo_cliente IS NULL");
		$count_user_sem_ven		=	Usuario::count("vendedor IS NULL");
		$count_user_ati			=	Usuario::count("status='active'");
		$count_user_con			=	Usuario::count("status='confirmed'");
		$count_user_pen			=	Usuario::count("status='pending'");
		$count_user_ina			=	Usuario::count("status='inactive'");

		$sem_emp_perc =intval($count_user_sem_emp*100/$count_user_tot);
		$sem_ven_perc	=intval($count_user_sem_ven*100/$count_user_tot);

		$ati_perc = intval($count_user_ati*100/$count_user_tot);
		$con_perc	= intval($count_user_con*100/$count_user_tot);
		$pen_perc	= intval($count_user_pen*100/$count_user_tot);
		$ina_perc	= intval($count_user_ina*100/$count_user_tot);

		$user_counter=[
		0=>		number_format($count_user_tot,0,'','.'),
		1=>		number_format($count_user_sem_emp,0,'','.'),	2=> $sem_emp_perc,
		3=>		number_format($count_user_sem_ven,0,'','.'),	4=> $sem_ven_perc,
		5=>		number_format($count_user_ati,0,'','.'),			6=> $ati_perc,
		7=>		number_format($count_user_con,0,'','.'),			8=> $con_perc,
		9=>		number_format($count_user_pen,0,'','.'),			10=> $pen_perc,
		11=> 	number_format($count_user_ina,0,'','.'),			12=> $ina_perc
		];

		$this->view->count_user=$user_counter;
		$this->view->userArray =(object) $users;
	}
	public function searchAction()
	{
        $service = new UsuarioService();
        $data = $service->getPageIndex();

        if ($data['page']->total_items < 1) {
            $this->flash->notice("A busca não encontrou usuários.");
        }

        $this->view->page = $data['page'];
        $this->view->userquery = $data['userquery'];
        $this->view->pick('usuario/index');
	}

	public function changeStatusAction()
	{
		$this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
		$response = new \Phalcon\Http\Response();
		if ($this->request->isPost()) {
			$post = $this->request->getPost();
			if (isset($post['id'])) {
				if(isset($post['state'])		  &&
					$post['state'] == 'pending'   ||
					$post['state'] == 'confirmed' ||
					$post['state'] == 'active'	||
					$post['state'] == 'inactive'
				){
					$id = intval($post['id']);
					$status = $post['state'];
					$user=new Usuario();
					$user->id = $id;
					$user->status =$status;
					$user->_runValidator =false;
					$user->edit();
					$response->setContent('ok');
				} else{
					$response->setContent('{"not ok":"Parametro state invalido"}');
				}
			} else {
				$response->setContent('{"not ok":"Parametro ID obrigatorio"}');
			}
		}
		return $response;
	}
	public function editAction($id){
		if($this->session->auth['role'] == 'administrador' OR $this->session->auth['role'] == 'vendedor'):
		if (!$this->request->isPost()):
			$Usuario = Usuario::findFirstById($id);
			if (!$Usuario):
				$this->flash->error("Usuario não encontrado.");

				return $this->dispatcher->forward(
					[
						"controller" => "usuario",
						"action"	 => "index",
					]
				);
			endif;
			//$vendedor = Usuario::findFirstById($Usuario->vendedor);
			//$vendedor = Usuario::find(["order"=>"name"]);

			$role = $this->session->auth['role'];
			
			$log_produtos = $this->modelsManager->createBuilder()
			->columns(['LogProdutoUsuarioDateView.codigo_produto',
			'LogProdutoUsuarioDateView.view_count',
			'LogProdutoUsuarioDateView.date',
			'ProdutoCore.descricao_site'
        	])
        	->from('LogProdutoUsuarioDateView')
        	->join('ProdutoCore', 'ProdutoCore.codigo_produto = LogProdutoUsuarioDateView.codigo_produto')
        	->where('LogProdutoUsuarioDateView.usuario_id = :user_id:',["user_id"=>$id])
        	->orderBy('LogProdutoUsuarioDateView.id DESC')
	        ->getQuery()
        	->execute();

			//dd($log_produtos);

			if(count($log_produtos)==0){
				$log_produtos = "NOT";
			}

			$this->view->role = $role;
			$this->view->log_produtos = $log_produtos;
			$this->view->edituser = true;
			$this->view->vendedor = $Usuario->vendedor;
			$this->view->user = $Usuario;
			$this->view->form = new UsuarioForm($Usuario, array('edit' => true));
			$this->persistent->searchParams = [];
		endif;
		else:
			return $this->response->redirect('/');
		endif;
	}
	public function saveAction()
	{
		// try {
		if (!$this->request->isPost()) {
			return $this->dispatcher->forward(
				[
					"controller" => "usuario",
					"action"	 => "index",
				]
			);
		}

		$id = $this->request->getPost("id", "int");
		$Usuario = Usuario::findFirstById($id);
		$this->view->user = $Usuario;
		if (!$Usuario) {
			$this->flash->error("Usuario inexistente.");

			return $this->dispatcher->forward(
				[
					"controller" => "usuario",
					"action"	 => "index",
				]
			);
		}

		$form = new UsuarioForm;
		$this->view->form = $form;

		$data = $this->request->getPost();

		$userType = $this->request->getPost("usuario_tipo", "int");
		if(intval($userType) == 1 || intval($userType) == 2){ // Administrador or Vendedor
			$data['vendedor'] = $data['id'];
			// $this->flash->notice(json_encode($data));
		}

		if (!$form->isValid($data, $Usuario)) {
			foreach ($form->getMessages() as $message) {
				$this->flash->error($message);
			}
			$Usuario = Usuario::findFirstById($id);
			if (!$Usuario) {
				$this->flash->error("Usuario não encontrado.");

				return $this->dispatcher->forward(
					[
						"controller" => "usuario",
						"action"	 => "index",
					]
				);
			}
			$this->view->user = $Usuario;

			return $this->dispatcher->forward(
				[
					"controller" => "usuario",
					"action"	 => "edit",
					"params"	 => [$id]
				]
			);
		}
		$Usuario->_runValidator=false;
		if ($Usuario->save() == false) {
			foreach ($Usuario->getMessages() as $message) {
				$this->flash->error($message);
			}

			return $this->dispatcher->forward(
				[
					"controller" => "usuario",
					"action"	 => "edit",
					"params"	 => [$id]
				]
			);
		}

		$vendedor = Usuario::findFirstById($Usuario->vendedor);
		//$vendedor = Usuario::find(["order"=>"name"]);
		$this->view->vendedor = $vendedor;
		$form->clear();
		$this->flash->success("Usuario atualizado com sucesso.");
		$this->persistent->resetSearchParams = true;
		$this->persistent->remove('searchParams');
		$this->response->redirect("usuario/index");
		// return $this->dispatcher->forward(
		// 	[
		// 		"controller" => "usuario",
		// 		"action"	 => "edit",
		// 		"params"	 => [$id]
		// 	]
		// );

		// } catch  (\Exception $e) {
		// 	echo get_class($e), ": ", $e->getMessage(), "\n";
		// 	echo " File=", $e->getFile(), "\n";
		// 	echo " Line=", $e->getLine(), "\n";
		// 	echo $e->getTraceAsString();
		// }
	}

	public function pendingtableAction(){
		if($this->session->auth['role'] == 'administrador'):

		$query_pending_user = "SELECT name,email,confirm_code,created_at FROM Usuario WHERE status='pending' ORDER BY id DESC";
		$result_query_pending_user = $this->modelsManager->createQuery($query_pending_user);
		$execute_query_pending_user = $result_query_pending_user->execute();

		$this->view->pending_user=$execute_query_pending_user;

		else:
		return $this->response->redirect(
		'/'
		);

		endif;
	}

	public function pendentesAction(){
	}

	public function meuvendedorAction(){
		$user_id = $this->session->auth['id'];
		
		$vendedor_id		= Usuario::findFirstById($user_id);
		$vendedor			= Usuario::findFirstById($vendedor_id->vendedor);
		$vendedor_descricao	= UsuarioVendedorDescricao::findFirstByUserId($vendedor_id->vendedor);

		$this->view->vendedor = $vendedor;
		$this->view->vendedor_descricao = $vendedor_descricao;
	}
}
