<?php

class StyleElements extends \Phalcon\Mvc\Model
{

    public $id;
    public $name;
    public $string;
    public $content;
    public $type;
    public $status;
    public $blank;
    public $order;
    public $created_at;
    public $updated_at;

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
        return 'style_elements';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoDescricao[]|ProdutoDescricao
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoDescricao
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
