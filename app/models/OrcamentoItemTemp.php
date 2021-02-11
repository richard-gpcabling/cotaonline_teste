<?php

class OrcamentoItemTemp extends Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $codigo_produto;

    /**
     *
     * @var integer
     */
    public $quantidade;

    /**
     *
     * @var double
     */
    public $valor_unitario;

    /**
     *
     * @var integer
     */
    public $open;

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
        $this->setSource("orcamento_item_temp");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'orcamento_item_temp';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrcamentoItemTemp[]|OrcamentoItemTemp|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrcamentoItemTemp|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
