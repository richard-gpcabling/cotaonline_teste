<?php

class UsuarioTipo extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $nome;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $origem;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Usuario', 'usuario_tipo', ['alias' => 'Usuario']);
        $this->hasMany('id', 'UsuarioView', 'usuario_tipo', ['alias' => 'UsuarioView']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'usuario_tipo';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsuarioTipo[]|UsuarioTipo
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsuarioTipo
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
