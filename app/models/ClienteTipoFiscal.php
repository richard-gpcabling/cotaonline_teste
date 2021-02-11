<?php

class ClienteTipoFiscal extends \Phalcon\Mvc\Model
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
    public $tipo_fiscal;

    /**
     *
     * @var string
     */
    public $custo_sys_string;

    /**
     *
     * @var string
     */
    public $timestamp;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        //$this->setSchema("kierkegaard");
        //$this->setSource("cliente_tipo_fiscal");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'cliente_tipo_fiscal';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ClienteTipoFiscal[]|ClienteTipoFiscal|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ClienteTipoFiscal|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
