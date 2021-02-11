<?php

class AclController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Permissões');
        parent::initialize();
    }

    public function indexAction()
    {
    	$administrador = UsuarioView::find(['conditions' =>'usuario_tipo=1']);
    	$vendedor = UsuarioView::find(['conditions' =>'usuario_tipo=2']);
    	$parceiro = UsuarioView::find(['conditions' =>'usuario_tipo=3']);
    	$cliente = UsuarioView::find(['conditions' =>'usuario_tipo=4']);
    	$this->view->administrador= $administrador;
    	$this->view->vendedor= $vendedor;
    	$this->view->parceiro= $parceiro;
    	$this->view->cliente= $cliente;
    }
    public function saveAction()
	{
		$this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
		$response = new \Phalcon\Http\Response();
		if ($this->request->isPost()) {
			$post = $this->request->getPost();
			$error =false;
			if (!isset($post['role']) && !isset($post['action']) ) {
				$errormsg = 'role ou action não enviados';
				$error = true;
			}
				/* Role List*/
				if($post['role']=='administrador'){$roleId =1;} 
				elseif($post['role']=='vendedor'){$roleId =2;}
				elseif($post['role']=='parceiro'){$roleId =3;}
				elseif($post['role']=='cliente'){$roleId =4;}
				else {
					$errormsg = 'role inexistente';
					$error = true;
				}
				/* end Role list*/

			if($error){
				// something went wrong
				$response->setContent('{"not ok":"'.$errormsg.'"}'); 
			} else {
				$UsuarioView = UsuarioView::findFirst([
					"conditions" => "usuario_tipo = ?1",
					"bind"       => [
					1 => $roleId
					]
				]);

				if (!$UsuarioView) {
					// can't find view
					$response->setContent('{"not ok":"View não encontrada"}');
				}else{ 
					/* Action List*/
					if($post['action']=="c_p"){$UsuarioView->c_p=$post['value'];}
					elseif($post['action']=="c_o"){$UsuarioView->c_o=$post['value'];}
					elseif($post['action']=="a_p"){$UsuarioView->a_p=$post['value'];}
					elseif($post['action']=="a_ep"){$UsuarioView->a_ep=$post['value'];}
					elseif($post['action']=="m_ur"){$UsuarioView->m_ur=$post['value'];}
					elseif($post['action']=="m_unr"){$UsuarioView->m_unr=$post['value'];}
					elseif($post['action']=="c_cr"){$UsuarioView->c_cr=$post['value'];}
					elseif($post['action']=="c_cnr"){$UsuarioView->c_cnr=$post['value'];}
					elseif($post['action']=="o_ur"){$UsuarioView->o_ur=$post['value'];}
					elseif($post['action']=="o_unr"){$UsuarioView->o_unr=$post['value'];}
					elseif($post['action']=="o_eur"){$UsuarioView->o_eur=$post['value'];}
					elseif($post['action']=="o_eunr"){$UsuarioView->o_eunr=$post['value'];}
					elseif($post['action']=="d_me"){$UsuarioView->d_me=$post['value'];}
					elseif($post['action']=="e_me"){$UsuarioView->e_me=$post['value'];}
					elseif($post['action']=="e_pag"){$UsuarioView->e_pag=$post['value'];}
					elseif($post['action']=="p_u"){$UsuarioView->p_u=$post['value'];}
					elseif($post['action']=="a_u"){$UsuarioView->a_u=$post['value'];}
					elseif($post['action']=="c_nu"){$UsuarioView->c_nu=$post['value'];}
					elseif($post['action']=="c_ne"){$UsuarioView->c_ne=$post['value'];}
					elseif($post['action']=="a_ecc"){$UsuarioView->a_ecc=$post['value'];}
					elseif($post['action']=="e_menu"){$UsuarioView->e_menu=$post['value'];}
					elseif($post['action']=="logs"){$UsuarioView->logs=$post['value'];}
					/*end Action List */
					$UsuarioView->update();
					$response->setContent(json_encode($UsuarioView)); // good news everyone!
				}
			}
		} else {
			$response->setContent('{"not ok":"Requisição inválida"}');
		}

		return $response;
	}
	private function validateAction($action)
	{
		$validActions = array("m_ur","m_unr","c_cr","c_cnr","o_ur","o_unr","d_me","e_me","e_pag","p_u","a_u","c_nu","c_ne","a_ecc","e_menu","logs");
		if (in_array($action, $validActions)){ return true;}
		return false;
	}
}
