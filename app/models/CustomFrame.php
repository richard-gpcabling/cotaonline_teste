<?php

class CustomFrame extends \Phalcon\Mvc\Model
{

    public $id;
    public $nome;
    public $produtos;
    public $aleatorio_por_fabricante;
    public $autoname;
    public $list;
    public $custom;
    public $custom_content;
    public $publish_date;
    public $updated_at;
    public $end_date;
    public $view_count;

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
        return 'custom_frame';
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
