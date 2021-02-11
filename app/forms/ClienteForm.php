<?php

namespace App\Forms;

use ClienteTipoFiscal;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Numericality;
use UnidadePolicom;

class ClienteForm extends Form
{

	/**
	 * Initialize the products form
	 */
	public function initialize($entity = null, $options = array())
	{
		$associate = new Hidden("associate");
		if (isset($options['associate']) && $options['associate'] ) {
			$associate->setDefault(true);
		} 
		$this->add($associate);

		$usuario_atualizou = new Hidden("usuario_atualizou");
		$usuario_atualizou->setAttributes (array("usuario_atualizou"));
		$this->add($usuario_atualizou);

		$codigoPolicom = new Hidden("codigo_policom");
		$this->add($codigoPolicom);

		$this->add(new Hidden("nome"));
		$nome = new Text("nome");
		$nome->setLabel("Razão Social *");
		$nome->setFilters(array('striptags', 'string'));
		$nome->addValidators(array(
			new PresenceOf(array(
				'message' => 'Razão Social obrigatória'
			))
		));
		$this->add($nome);

		$apelido = new Text("apelido");
		$apelido->setLabel("Nome Fantasia *");
		$apelido->setFilters(array('striptags', 'string'));
		$apelido->addValidators(array(
			new PresenceOf(array(
				'message' => 'Nome Fantasia obrigatório'
			))
		));
		$this->add($apelido);

		$email = new Text("email");
		$email->setLabel("Email *");
		$email->setFilters(array('striptags', 'string'));
		$email->addValidators(array(
			new PresenceOf(array(
				'message' => 'Email é obrigatório'
			))
		));
		$this->add($email);

		$telefone = new Text("telefone");
		$telefone->setLabel("Telefone *");
		$telefone->setFilters(array('striptags', 'string'));
		$telefone->addValidators(array(
			new PresenceOf(array(
				'message' => 'Telefone obrigatório.'
			))
		)); 
		$this->add($telefone);

		$site = new Text("site");
		$site->setLabel("Site");
		$site->setFilters(array('striptags', 'string'));
		$this->add($site);

		$transportadora_preferencia = new Text("transportadora_preferencia");
		$transportadora_preferencia->setLabel("Transportadora preferencial");
		$transportadora_preferencia->setFilters(array('striptags', 'string'));
		$this->add($transportadora_preferencia);

		$codigo_representante = new Text("codigo_representante");
		$codigo_representante->setLabel("Código representante");
		$codigo_representante->setFilters(array('striptags', 'string'));
		$this->add($codigo_representante);


		$prospect = new Text("prospect");
		$prospect->setLabel("Prospecto *");
		$prospect->setFilters(array('striptags', 'string'));
		$prospect->addValidators(array(
			new PresenceOf(array(
				'message' => 'Prospecto obrigatório'
			))
		));
		$this->add($prospect);

		$model =  $this->modelsManager->createBuilder()
					->columns(["Usuario.*"])
					->from('Usuario')
					->leftjoin('UsuarioTipo','UsuarioTipo.id=Usuario.usuario_tipo')
					->where(' UsuarioTipo.id= 2')
					->getQuery()
					->execute();
		if (isset($options['edit']) && $options['edit']) { // new clients can't choose salesman yet.
				$vendedor_interno = new Select('vendedor_interno', $model, array(
					'using'	  => array('id', 'name'),
					'useEmpty'   => true,
					'emptyText'  => 'Selecione...',
					'emptyValue' => ''
				));
				$vendedor_interno->setLabel('Vendedor interno');
				$this->add($vendedor_interno);

				$vendedor_externo_1 = new Select('vendedor_externo_1', $model, array(
					'using'	  => array('id', 'name'),
					'useEmpty'   => true,
					'emptyText'  => 'Selecione...',
					'emptyValue' => ''
				));
				$vendedor_externo_1->setLabel('Vendedor externo 1');
				$this->add($vendedor_externo_1);

				$vendedor_externo_2 = new Select('vendedor_externo_2', $model, array(
					'using'	  => array('id', 'name'),
					'useEmpty'   => true,
					'emptyText'  => 'Selecione...',
					'emptyValue' => ''
				));
				$vendedor_externo_2->setLabel('Vendedor externo 2');
				$this->add($vendedor_externo_2);
		}
		$comentario = new Text("comentario");
		$comentario->setLabel("Comentario");
		$comentario->setFilters(array('striptags', 'string'));
		$this->add($comentario);

		$ultima_compra = new Text("ultima_compra");
		$ultima_compra->setLabel("Última compra");
		$ultima_compra->setFilters(array('striptags', 'string'));
		$this->add($ultima_compra);

		$cliente_fornecedorData = array(
			'cliente' => 'cliente',
			'fornecedor' => 'fornecedor'
		);
		$cliente_fornecedor = new Select('cliente_fornecedor', $cliente_fornecedorData, array(
			'using'	     => array('id', 'sigla'),
			'useEmpty'   => false,
			'emptyText'  => '...',
			'emptyValue' => ''
		));
		$this->add($cliente_fornecedor);

		
		$usuario_cadastro = new Text("usuario_cadastro");
		$usuario_cadastro->setLabel("Usuário cadastro");
		$usuario_cadastro->setFilters(array('striptags', 'string'));
		$this->add($usuario_cadastro);
		
		$limite_credito = new Text("limite_credito");
		$limite_credito->setLabel("Limite de crédito");
		$limite_credito->setFilters(array('striptags', 'string'));
		$this->add($limite_credito);

		$valor_minimo = new Text("valor_minimo");
		$valor_minimo->setLabel("Valor mínimo");
		$valor_minimo->setFilters(array('striptags', 'string'));
		$this->add($valor_minimo);

		$data_cadastro = new Text("data_cadastro");
		$data_cadastro->setLabel("data_cadastro");
		$data_cadastro->setFilters(array('striptags', 'string'));
		$this->add($data_cadastro);

		$data_atualizacao = new Text("data_atualizacao");
		$data_atualizacao->setLabel("Data da úlima atualização");
		$data_atualizacao->setFilters(array('striptags', 'string'));
		$this->add($data_atualizacao);

		$cnpj_cpf = new Text("cnpj_cpf");
		$cnpj_cpf->setLabel("CNPJ *");
		$cnpj_cpf->setFilters(array('striptags', 'string'));
		$cnpj_cpf->addValidators(array(
			new PresenceOf(array(
				'message' => 'CNPJ obrigatório'
			))
		));
		$this->add($cnpj_cpf);
		
		$inscricao_estadual = new Text("inscricao_estadual");
		$inscricao_estadual->setLabel("Inscrição estadual");
		$inscricao_estadual->setFilters(array('striptags', 'string'));
		$this->add($inscricao_estadual);
		
		$rg = new Text("rg");
		$rg->setLabel("rg");
		$rg->setFilters(array('striptags', 'string'));
		$this->add($rg);
		
		$natureza = new Text("natureza");
		$natureza->setLabel("natureza");
		$natureza->setFilters(array('striptags', 'string'));
		$this->add($natureza);

		$tipo = new Select('tipo', ClienteTipoFiscal::find(), array(
			'using'	  => array('id', 'titulo'),
			'useEmpty'   => true,
			'emptyText'  => '...',
			'emptyValue' => ''
		));
		$tipo->setLabel('Tipo*');
		$tipo->addValidators(array(
			new PresenceOf(array(
				'tipo' => 'Tipo é obrigatório'
			))
		));
		$this->add($tipo);
		
		$cep = new Text("cep");
		$cep->setLabel("CEP");
		$cep->setFilters(array('striptags', 'string'));
		$this->add($cep);

		$endereco = new Text("endereco");
		$endereco->setLabel("Endereço");
		$endereco->setFilters(array('striptags', 'string'));
		$this->add($endereco);
		
		$bairro = new Text("bairro");
		$bairro->setLabel("Bairro");
		$bairro->setFilters(array('striptags', 'string'));
		$this->add($bairro);
		
		$cidade = new Text("cidade");
		$cidade->setLabel("Cidade");
		$cidade->setFilters(array('striptags', 'string'));
		$this->add($cidade);
		
		$estados = array(
			'' => '...',
			'AC' => 'Ac',
			'AL' => 'Al',
			'AM' => 'Am',
			'AP' => 'Ap',
			'BA' => 'Ba',
			'CE' => 'Ce',
			'DF' => 'Df',
			'ES' => 'Es',
			'GO' => 'Go',
			'MA' => 'Ma',
			'MG' => 'Mg',
			'MS' => 'Ms',
			'MT' => 'Mt',
			'PA' => 'Pa',
			'PB' => 'Pb',
			'PE' => 'Pe',
			'PI' => 'Pi',
			'PR' => 'Pr',
			'RJ' => 'Rj',
			'RN' => 'Rn',
			'RO' => 'Ro',
			'RR' => 'Rr',
			'RS' => 'Rs',
			'SC' => 'Sc',
			'SE' => 'Se',
			'SP' => 'Sp',
			'TO' => 'To',
		);
		$estado = new Select('estado', $estados, array(
			 'using'	  => array('id', 'sigla'),
			 'useEmpty'   => false,
			 'emptyText'  => '...',
			 'emptyValue' => ''
		));
		$estado->setLabel("Estado*");
		$estado->addValidators(array(
			new PresenceOf(array(
				'estado' => 'estado é obrigatório'
			))
		));
		$this->add($estado);
		
		$pais = new Text("pais");
		$pais->setLabel("País");
		$pais->setFilters(array('striptags', 'string'));
		$this->add($pais);

		$mark_up = new Text("mark_up");
		$mark_up->setLabel("Mark up");
		$mark_up->setFilters(array('striptags', 'string'));
		$this->add($mark_up);

		$unidade_policom = new Select('unidade_policom', UnidadePolicom::find(), array(
			'using'	  => array('id', 'nome'),
			'useEmpty'   => false,
			// 'emptyText'  => '...',
			'emptyValue' => ''
		));
		$unidade_policom->setLabel('Unidade Policom');
		$this->add($unidade_policom);

		$values = array(
			'0' => 'bloqueado',
			'1' => 'liberado',
			'2' => 'desbloqueado'
		);
		$status = new Select('status', $values, array(
			 'using'	  => array('id', 'sigla'),
			 'useEmpty'   => false,
			 'emptyText'  => '...',
			 'emptyValue' => ''
		));
		$this->add($status);
	}   
}