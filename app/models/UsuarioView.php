<?php

class UsuarioView extends \Phalcon\Mvc\Model
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
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $usuario_tipo;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $c_p;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $c_o;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $a_p;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $a_ep;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $m_ur;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $m_unr;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $c_cr;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $c_cnr;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $o_ur;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $o_unr;
    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $o_eur;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $o_eunr;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $d_me;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $e_me;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $e_pag;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $p_u;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $a_u;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $c_nu;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $c_ne;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $a_ecc;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $e_menu;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $logs;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('usuario_tipo', '\UsuarioTipo', 'id', ['alias' => 'UsuarioTipo']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'usuario_view';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsuarioView[]|UsuarioView
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsuarioView
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
