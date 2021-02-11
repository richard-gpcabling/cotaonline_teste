<?php

class ProdutoHomeCard extends \Phalcon\Mvc\Model
{
    public $id;
    public $codigo_produto;


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
        return 'produto_home_card';
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
