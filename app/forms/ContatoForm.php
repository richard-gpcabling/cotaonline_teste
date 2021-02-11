<?php

namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;

class ContatoForm extends Form
{

// anexo
// newsletter

	/**
	 * Initialize the products form
	 */
	public function initialize($entity = null, $options = array())
	{
		if (!isset($options['read'])) {
			$element = new Text("id");
			$this->add($element->setLabel("Id"));
		} else {
			$this->add(new Hidden("id"));
		}

		$this->add(new Hidden("id"));

		$nome = new Text("nome_completo");
		$nome->setLabel("Nome Completo*");
		$nome->setFilters(array('striptags', 'string'));
		$nome->addValidators(array(
			new PresenceOf(array(
				'message' => 'Nome Completo é obrigatório'
			))
		));
		$this->add($nome);

		$empresa = new Text("empresa");
		$empresa->setLabel("Empresa*");
		$empresa->setFilters(array('striptags', 'string'));
		$empresa->addValidators(array(
			new PresenceOf(array(
				'message' => 'Empresa é obrigatório'
			))
		));
		$this->add($empresa);

		$cnpj_cpf = new Text("cnpj_cpf");
		$cnpj_cpf->setLabel("CNPJ");
		$cnpj_cpf->setFilters(array('striptags', 'string'));
		$this->add($cnpj_cpf);

		$email = new Text("e_mail");
		$email->setLabel("Email *");
		$email->setFilters(array('striptags', 'string'));
		$email->addValidators(array(
			new PresenceOf(array(
				'message' => 'Email é obrigatório'
			))
		));

		$this->add($email);

		$telefone_comercial = new Text("telefone_comercial");
		$telefone_comercial->setLabel("Telefone Comercial");
		$telefone_comercial->setFilters(array('striptags', 'string'));
		$this->add($telefone_comercial);

		$telefone_celular = new Text("telefone_celular");
		$telefone_celular->setLabel("Celular");
		$telefone_celular->setFilters(array('striptags', 'string'));
		$this->add($telefone_celular);

		$newsletter = new Check("newsletter");
		$newsletter->setLabel("Quero me cadastrar na newsletter");
		$newsletter->setDefault('1');
		$this->add($newsletter);

		$mensagem = new TextArea("mensagem");
		$mensagem->setLabel("Mensagem*");
		$mensagem->setFilters(array('striptags', 'string'));
		$mensagem->addValidators(array(
			new PresenceOf(array(
				'message' => 'Mensagem é obrigatório'
			))
		));
		$this->add($mensagem);
	}
}