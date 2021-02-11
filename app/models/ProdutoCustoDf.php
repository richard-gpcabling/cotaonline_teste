<?php

class ProdutoCustoDf extends \Phalcon\Mvc\Model
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
    public $produtoUcode;

    /**
     *
     * @var string
     */
    public $codigo_produto;

    /**
     *
     * @var string
     */
    public $origem;

    /**
     *
     * @var double
     */
    public $custo_consumidor_contribuinte_04;

    /**
     *
     * @var double
     */
    public $custo_consumidor_contribuinte_05;

    /**
     *
     * @var double
     */
    public $custo_consumidor_contribuinte_06;

    /**
     *
     * @var double
     */
    public $custo_consumidor_contribuinte_07;

    /**
     *
     * @var double
     */
    public $custo_consumidor_nao_contribuinte_04;

    /**
     *
     * @var double
     */
    public $custo_consumidor_nao_contribuinte_05;

    /**
     *
     * @var double
     */
    public $custo_consumidor_nao_contribuinte_06;

    /**
     *
     * @var double
     */
    public $custo_consumidor_nao_contribuinte_07;

    /**
     *
     * @var double
     */
    public $custo_revenda_normal_04;

    /**
     *
     * @var double
     */
    public $custo_revenda_normal_05;

    /**
     *
     * @var double
     */
    public $custo_revenda_normal_06;

    /**
     *
     * @var double
     */
    public $custo_revenda_normal_07;

    /**
     *
     * @var double
     */
    public $custo_revenda_simples_nacional_04;

    /**
     *
     * @var double
     */
    public $custo_revenda_simples_nacional_05;

    /**
     *
     * @var double
     */
    public $custo_revenda_simples_nacional_06;

    /**
     *
     * @var double
     */
    public $custo_revenda_simples_nacional_07;

    /**
     *
     * @var string
     */
    public $taxas_consumidor_contribuinte;

    /**
     *
     * @var string
     */
    public $taxas_consumidor_nao_contribuinte;

    /**
     *
     * @var string
     */
    public $taxas_revenda_normal;

    /**
     *
     * @var string
     */
    public $taxas_revenda_simples_nacional;

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
        $this->setSource("produto_custo_df");
    }
    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'produto_custo_df';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoCustoDf[]|ProdutoCustoDf|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoCustoDf|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
