<?php

namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;

class OrcamentoForm extends Form
{

	/**
	 * Initialize the products form
	 */
	public function initialize($entity = null, $options = array())
	{
		$this->add(new Hidden("id"));
		$values = array(
			'Salvo'=>'Salvo',
			'Só preços'=>'Só preços',
			'Em negociação'=> 'Em negociação',
			'Aprovado'=>'Aprovado',
			'Perdido'=>'Perdido'
		);

		if (isset($options['edit']) && $options['edit'] ){
			$status = new Select('status', $values, array(
				 'using'	  => array('id', 'sigla'),
				 'useEmpty'   => false,
				 'emptyText'  => '...',
				 'emptyValue' => ''
			));
		} else {
			$status = new Select('status', $values, array(
				 'using'	  => array('id', 'sigla'),
				 'useEmpty'   => false,
				 'emptyText'  => '...',
				 'emptyValue' => '',
				 'disabled'   =>true
			));
		}
		$status->setLabel('status');
		$this->add($status);

		$usuario_id = new hidden("usuario_id");
		$this->add($usuario_id);
		/*
		$custoValues = array(
			'' => '...',
			'CustoCabosAc' => 'CustoCabosAc',
			'CustoCabosAl' => 'CustoCabosAl',
			'CustoCabosAm' => 'CustoCabosAm',
			'CustoCabosAp' => 'CustoCabosAp',
			'CustoCabosBa' => 'CustoCabosBa',
			'CustoCabosCe' => 'CustoCabosCe',
			'CustoCabosDf' => 'CustoCabosDf',
			'CustoCabosEs' => 'CustoCabosEs',
			'CustoCabosGo' => 'CustoCabosGo',
			'CustoCabosMa' => 'CustoCabosMa',
			'CustoCabosMg' => 'CustoCabosMg',
			'CustoCabosMs' => 'CustoCabosMs',
			'CustoCabosMt' => 'CustoCabosMt',
			'CustoCabosPa' => 'CustoCabosPa',
			'CustoCabosPb' => 'CustoCabosPb',
			'CustoCabosPe' => 'CustoCabosPe',
			'CustoCabosPi' => 'CustoCabosPi',
			'CustoCabosPr' => 'CustoCabosPr',
			'CustoCabosRj' => 'CustoCabosRj',
			'CustoCabosRn' => 'CustoCabosRn',
			'CustoCabosRo' => 'CustoCabosRo',
			'CustoCabosRr' => 'CustoCabosRr',
			'CustoCabosRs' => 'CustoCabosRs',
			'CustoCabosSc' => 'CustoCabosSc',
			'CustoCabosSe' => 'CustoCabosSe',
			'CustoCabosSp' => 'CustoCabosSp',
			'CustoCabosTo' => 'CustoCabosTo',
			'CustoPrAc' => 'CustoPrAc',
			'CustoPrAl' => 'CustoPrAl',
			'CustoPrAm' => 'CustoPrAm',
			'CustoPrAp' => 'CustoPrAp',
			'CustoPrBa' => 'CustoPrBa',
			'CustoPrCe' => 'CustoPrCe',
			'CustoPrDf' => 'CustoPrDf',
			'CustoPrEs' => 'CustoPrEs',
			'CustoPrGo' => 'CustoPrGo',
			'CustoPrMa' => 'CustoPrMa',
			'CustoPrMg' => 'CustoPrMg',
			'CustoPrMs' => 'CustoPrMs',
			'CustoPrMt' => 'CustoPrMt',
			'CustoPrPa' => 'CustoPrPa',
			'CustoPrPb' => 'CustoPrPb',
			'CustoPrPe' => 'CustoPrPe',
			'CustoPrPi' => 'CustoPrPi',
			'CustoPrPr' => 'CustoPrPr',
			'CustoPrRj' => 'CustoPrRj',
			'CustoPrRn' => 'CustoPrRn',
			'CustoPrRo' => 'CustoPrRo',
			'CustoPrRr' => 'CustoPrRr',
			'CustoPrRs' => 'CustoPrRs',
			'CustoPrSc' => 'CustoPrSc',
			'CustoPrSe' => 'CustoPrSe',
			'CustoPrSp' => 'CustoPrSp',
			'CustoPrTo' => 'CustoPrTo',
			'CustoRjAc' => 'CustoRjAc',
			'CustoRjAl' => 'CustoRjAl',
			'CustoRjAm' => 'CustoRjAm',
			'CustoRjAp' => 'CustoRjAp',
			'CustoRjBa' => 'CustoRjBa',
			'CustoRjCe' => 'CustoRjCe',
			'CustoRjDf' => 'CustoRjDf',
			'CustoRjEs' => 'CustoRjEs',
			'CustoRjGo' => 'CustoRjGo',
			'CustoRjMa' => 'CustoRjMa',
			'CustoRjMg' => 'CustoRjMg',
			'CustoRjMs' => 'CustoRjMs',
			'CustoRjMt' => 'CustoRjMt',
			'CustoRjPa' => 'CustoRjPa',
			'CustoRjPb' => 'CustoRjPb',
			'CustoRjPe' => 'CustoRjPe',
			'CustoRjPi' => 'CustoRjPi',
			'CustoRjPr' => 'CustoRjPr',
			'CustoRjRj' => 'CustoRjRj',
			'CustoRjRn' => 'CustoRjRn',
			'CustoRjRo' => 'CustoRjRo',
			'CustoRjRr' => 'CustoRjRr',
			'CustoRjRs' => 'CustoRjRs',
			'CustoRjSc' => 'CustoRjSc',
			'CustoRjSe' => 'CustoRjSe',
			'CustoRjSp' => 'CustoRjSp',
			'CustoRjTo' => 'CustoRjTo',
			'CustoSpAc' => 'CustoSpAc',
			'CustoSpAl' => 'CustoSpAl',
			'CustoSpAm' => 'CustoSpAm',
			'CustoSpAp' => 'CustoSpAp',
			'CustoSpBa' => 'CustoSpBa',
			'CustoSpCe' => 'CustoSpCe',
			'CustoSpDf' => 'CustoSpDf',
			'CustoSpEs' => 'CustoSpEs',
			'CustoSpGo' => 'CustoSpGo',
			'CustoSpMa' => 'CustoSpMa',
			'CustoSpMg' => 'CustoSpMg',
			'CustoSpMs' => 'CustoSpMs',
			'CustoSpMt' => 'CustoSpMt',
			'CustoSpPa' => 'CustoSpPa',
			'CustoSpPb' => 'CustoSpPb',
			'CustoSpPe' => 'CustoSpPe',
			'CustoSpPi' => 'CustoSpPi',
			'CustoSpPr' => 'CustoSpPr',
			'CustoSpRj' => 'CustoSpRj',
			'CustoSpRn' => 'CustoSpRn',
			'CustoSpRo' => 'CustoSpRo',
			'CustoSpRr' => 'CustoSpRr',
			'CustoSpRs' => 'CustoSpRs',
			'CustoSpSc' => 'CustoSpSc',
			'CustoSpSe' => 'CustoSpSe',
			'CustoSpSp' => 'CustoSpSp',
			'CustoSpTo' => 'CustoSpTo'
		);
		if (isset($options['edit']) && $options['edit'] ){
			$custo = new Select('custo', $custoValues, array(
				 'using'	  => array('id', 'sigla'),
				 'useEmpty'   => false,
				 'emptyText'  => '...',
				 'emptyValue' => ''
			));
		} else { // Read Only
			$custo = new Text('custo',["readonly"=>true]);
		}
		$custo->setLabel("Tabela de custo:");
		$this->add($custo);

		if (isset($options['edit']) && $options['edit'] ){
			$unique_code = new Text("unique_code");
		} else {
			$unique_code = new Text("unique_code",["readonly"=>true]);
		}
		$unique_code->setLabel("Código");
		$unique_code->setFilters(array('striptags', 'string'));
		// $unique_code->addValidators(array(
		// 	 new PresenceOf(array(
		// 		 'message' => 'unique_code é obrigatório'
		// 	 ))
		// ));
		$this->add($unique_code);
		*/

		$observacao = new Text("observacao");
		$observacao->setLabel("Observação");
		$observacao->setFilters(array('striptags', 'string'));
		$this->add($observacao);


	}
}