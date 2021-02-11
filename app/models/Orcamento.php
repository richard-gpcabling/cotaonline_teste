<?php

class Orcamento extends \Phalcon\Mvc\Model
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
    public $usuario_id;

    /**
     *
     * @var string
     * @Column(type="string", length=11, nullable=true)
     */
    public $custo;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $unique_code;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $status;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $observacao;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $data_de_criacao;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $total;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $ucode;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $tabela;

    /**
     *
     * @var intenger
     * @Column(type="integer", length=1, nullable=false)
     */
    public $tipo;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'OrcamentoItem', 'orcamento_id', ['alias' => 'OrcamentoItem']);
        $this->belongsTo('usuario_id', '\Usuario', 'id', ['alias' => 'Usuario']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'orcamento';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Orcamento[]|Orcamento
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Orcamento
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
