<?php

class ProdutoViewCount extends \Phalcon\Mvc\Model
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
     * @Column(type="integer", length=11, nullable=true)
     */
    public $codigo_produto;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $count;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $dia;

    /**
     * Initialize method for model.
     */
    public function initialize(){
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'produto_view_count';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoViewCount[]|ProdutoViewCount
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoViewCount
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
