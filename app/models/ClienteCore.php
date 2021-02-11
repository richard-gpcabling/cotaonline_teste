<?php

class ClienteCore extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $clienteUcode;

    /**
     *
     * @var string
     */
    public $codigo_policom;

    /**
     *
     * @var string
     */
    public $cadastrada_em;

    /**
     *
     * @var string
     */
    public $origem_de_compra;

    /**
     *
     * @var string
     */
    public $destino;

    /**
     *
     * @var string
     */
    public $razao_social;

    /**
     *
     * @var string
     */
    public $nome;

    /**
     *
     * @var string
     */
    public $cnpj_cpf;

    /**
     *
     * @var string
     */
    public $vendedor;

    /**
     *
     * @var integer
     */
    public $mark_up_geral;

    /**
     *
     * @var string
     */
    public $mark_up_fabricantes;

    /**
     *
     * @var integer
     */
    public $tipo_fiscal;

    /**
     *
     * @var integer
     */
    public $canal;

    /**
     *
     * @var string
     */
    public $revendedor;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     * Initialize method for model.
    **/
    public function initialize()
    {
        //$this->setSchema("kierkegaard");
        //$this->setSource("cliente_core");
        $this->belongsTo('cliente_tipo_fiscal', '\ClienteTipoFiscal', 'id', ['alias' => 'ClienteTipoFiscal']);
    }
  
    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'cliente_core';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ClienteCore[]|ClienteCore|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ClienteCore|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
