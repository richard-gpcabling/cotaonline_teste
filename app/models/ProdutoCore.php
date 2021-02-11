<?php

class ProdutoCore extends \Phalcon\Mvc\Model
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
    public $codigo_produto;

    /**
     *
     * @var string
     */
    public $sigla_fabricante;

    /**
     *
     * @var string
     */
    public $origem;

    /**
     *
     * @var string
     */
    public $descricao_sys;

    /**
     *
     * @var string
     */
    public $descricao_site;

    /**
     *
     * @var string
     */
    public $ref;

    /**
     *
     * @var string
     */
    public $ncm;

    /**
     *
     * @var string
     */
    public $unidade_venda;

    /**
     *
     * @var string
     */
    public $moeda_venda;

    /**
     *
     * @var string
     */
    public $tipo_fiscal;

    /**
     *
     * @var integer
     */
    public $estoque_total;

    /**
     *
     * @var integer
     */
    public $promo;

    /**
     *
     * @var integer
     */
    public $peep;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        //$this->setSchema("kierkegaard");
        $this->setSource("produto_core");
    }
    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'produto_core';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoCore[]|ProdutoCore|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoCore|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
