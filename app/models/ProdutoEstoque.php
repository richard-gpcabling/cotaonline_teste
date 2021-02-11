<?php

class ProdutoEstoque extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var integer
     * @Unique
     * @Column(type="char", length=7, nullable=false)
     */
    public $codigo_produto;

    /**
     *
     * @var integer
     * @Column(type="tinytext", nullable=false)
     */
    public $estoque;

    /**
     *
     * @var integer
     * @Column(type="decimal", length=11,2, nullable=false)
     */
    public $total;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'produto_estoque';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoEstoque[]|ProdutoEstoque
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoEstoque
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}