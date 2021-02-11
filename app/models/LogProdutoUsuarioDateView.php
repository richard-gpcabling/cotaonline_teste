<?php

class LogProdutoUsuarioDateView extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=5, nullable=false)
     */
    public $codigo_produto;

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $usuario_id;

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $view_count;

    /**
     *
     * @var string
     * @Column(type="string", lenght=10, nullable=false)
     */
    public $date;

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
        return 'log_produto_usuario_date_view';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return LogProdutoUsuarioDateView[]|LogProdutoUsuarioDateView
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return LogProdutoUsuarioDateView
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
