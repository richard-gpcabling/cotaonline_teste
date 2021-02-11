<?php

class ViewUsuarioClienteCustoVars extends \Phalcon\Mvc\Model
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
    public $clienteUcode;

    /**
     *
     * @var integer
     */
    public $mark_up_geral;

    /**
     *
     * @var string
     */
    public $mark_up_fabricantes;

    /**
     *
     * @var string
     */
    public $origem_cliente;

    /**
     *
     * @var string
     */
    public $destino;

    /**
     *
     * @var string
     */
    public $tabela_de_custo;

    /**
     *
     * @var string
     */
    public $coluna_custo;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        //$this->setSchema("kierkegaard");
        $this->setSource("view_usuario_cliente_custo_vars");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'view_usuario_cliente_custo_vars';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ViewUsuarioClienteCustoVars[]|ViewUsuarioClienteCustoVars|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ViewUsuarioClienteCustoVars|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
