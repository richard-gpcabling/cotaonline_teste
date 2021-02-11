<?php

class Pages extends \Phalcon\Mvc\Model
{

    public $id;
    public $uri;
    public $title;
    public $content;
    public $created_at;
    public $update_at;

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
        return 'pages';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoCore[]|ProdutoCore
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoCore
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
