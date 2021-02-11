<?php

class UsuarioVendedorDescricao extends \Phalcon\Mvc\Model
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
    public $foto;

    /**
     *
     * @var string
     */
    public $celular;

    /**
     *
     * @var string
     */
    public $telefone_fixo;

    /**
     *
     * @var string
     */
    public $ramal;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        //$this->setSchema("kierkegaard");
        $this->setSource("usuario_vendedor_descricao");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'usuario_vendedor_descricao';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsuarioVendedorDescricao[]|UsuarioVendedorDescricao|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsuarioVendedorDescricao|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
