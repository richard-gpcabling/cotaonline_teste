<?php

class ProdutoFabricante extends \Phalcon\Mvc\Model
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
    public $nome;

    /**
     *
     * @var string
     */
    public $sigla;

    /**
     *
     * @var string
     */
    public $logo;

    /**
     *
     * @var string
     */
    public $preview;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     * @var integer
     */
    public const FABRICANTE_INATIVO = 0;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        //$this->setSchema("kierkegaard");
        $this->setSource("produto_fabricante");
    }
    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'produto_fabricante';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoFabricante[]|ProdutoFabricante|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoFabricante|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
