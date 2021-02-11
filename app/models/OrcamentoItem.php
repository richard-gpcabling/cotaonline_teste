<?php

class OrcamentoItem extends \Phalcon\Mvc\Model
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
    public $orcamento_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $produto_id;

    /**
     *
     * @var integer
     * @Column(type="string", length=5, nullable=false)
     */
    public $codigo_produto;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $quantidade;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $unitario;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $subtotal;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $rawcost;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $fator;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $markup;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('orcamento_id', '\Orcamento', 'id', ['alias' => 'Orcamento']);
        $this->belongsTo('codigo_produto', '\ProdutoCore', 'codigo_produto', ['alias' => 'ProdutoCore']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'orcamento_item';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrcamentoItem[]|OrcamentoItem
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrcamentoItem
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
