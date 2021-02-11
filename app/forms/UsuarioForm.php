<?php

namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Tag\textField;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Usuario;
use UsuarioTipo;

class UsuarioForm extends Form
{

	/**
	 * Initialize the products form
	 */
	public function initialize($entity = null, $options = array())
	{

		$this->add(new Hidden("id"));

		// $name = new Text("name");
		// $name->setLabel("Nome");
		// $name->setFilters(array('striptags', 'string'));
		// $name->addValidators(array(
		// 	new PresenceOf(array(
		// 		'message' => 'Name is required'
		// 	))
		// ));
		// $this->add($name);

		// $email = new Text("email");
		// $email->setLabel("Email");
		// $email->setFilters(array('striptags', 'string'));
		// $email->addValidators(array(
		// 	new PresenceOf(array(
		// 		'message' => 'Email é obrigatório'
		// 	))
		// ));
		// $this->add($email);
 
        $values = array(
			'active' => 'ativo',
			'inactive' => 'inativo'
		);
		$status = new Select('status', $values, array(
			 'using'	  => array('id', 'sigla'),
			 'useEmpty'   => false,
			 'emptyText'  => '...',
			 'emptyValue' => ''
		));
		// $status = new Text("status");
		// $status->setLabel("status");
		// $status->addValidators(array(
		// 	new PresenceOf(array(
		// 		'message' => 'status is required'
		// 	))
		// ));
		$this->add($status);
		// var_dump($this->view->auth);
		
		if ($this->view->auth['role']=='administrador') {
			$usuario_tipo  = new Select('usuario_tipo', UsuarioTipo::find(['order'=>'nome ASC']), array(
				 'using'		  => array('id', 'nome'),
				 'useEmpty'   => true,
				 'emptyText'  => '...',
				 'emptyValue' => ''
			));
		} else {
			$usuario_tipo  = new Select('usuario_tipo', UsuarioTipo::find(['order'=>'nome ASC']), array(
				 'using'	  => array('id', 'nome'),
				 'useEmpty'   => true,
				 'emptyText'  => '...',
				 'emptyValue' => '',
				 'disabled' =>'disabled'
			));
			
		}
		$usuario_tipo->addValidators(array(
			new PresenceOf(array(
				'message' => 'Tipo de usuario é obrigatório'
			))
		));
		$usuario_tipo ->setLabel("Tipo de usuário ");
		$this->add($usuario_tipo );
		$vendedores = Usuario::find(
			["conditions"=>"usuario_tipo = '2' AND status = 'active' ORDER BY name"]
			);
		if ($this->view->auth['role']=='administrador') {
			$vendedor  = new Select('vendedor', $vendedores , array(
				 'using'	  => array('id', 'name'),
				 'useEmpty'   => true,
				 'emptyText'  => '...',
				 'emptyValue' => ''
			));
			$vendedor ->setLabel("Vendedor");
			$vendedor ->addValidators(array(
				new PresenceOf(array(
					'message' => 'Vendedor obrigatório'
				))
			));
			$this->add($vendedor );
		}else {
			$vendedorLabel = new Text('vendedorLabel');
			$vendedorLabel ->setLabel("Vendedor ");
			$this->add($vendedorLabel );
			// $vendedor
		}
	}
}