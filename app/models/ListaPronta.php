<?php

class ListaPronta extends \Phalcon\Mvc\Model
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
    public $titulo;

    /**
     *
     * @var string
     */
    public $slug;

    /**
     *
     * @var string
     */
    public $descricao;

    /**
     *
     * @var string
     */
    public $produtos;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $criado_por;

    /**
     *
     * @var integer
     */
    public $atualizado_por;

    /**
     *
     * @var string
     */
    public $created_at;

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
        /*$this->setSchema("kierkegaard");*/
        $this->setSource("lista_pronta");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'lista_pronta';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ListaPronta[]|ListaPronta|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ListaPronta|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
