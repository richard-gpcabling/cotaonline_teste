<?php

class LogProdutoContent extends \Phalcon\Mvc\Model
{
    public $id;
    public $user_id;
    public $codigo_produto;
    public $content;
    public $timestamp;

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
        return 'log_produto_content';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return LogProdutoView[]|LogProdutoView
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return LogProdutoView
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
