<?php

class UnidadePolicom extends \Phalcon\Mvc\Model
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
    public $sigla;

    /**
     *
     * @var string
     */
    public $nome;

    /**
     *
     * @var string
     */
    public $descricao;

    /**
     *
     * @var string
     */
    public $rua;

    /**
     *
     * @var string
     */
    public $bairro;

    /**
     *
     * @var string
     */
    public $cidade;

    /**
     *
     * @var string
     */
    public $estado;

    /**
     *
     * @var string
     */
    public $cep;

    /**
     *
     * @var string
     */
    public $telefone;

    /**
     *
     * @var string
     */
    public $representante;

    /**
     *
     * @var string
     */
    public $info;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("unidade_policom");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'unidade_policom';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UnidadePolicom[]|UnidadePolicom|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UnidadePolicom|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
