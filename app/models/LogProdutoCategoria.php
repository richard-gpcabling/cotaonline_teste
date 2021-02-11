<?php


class LogProdutoCategoria extends Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    private $id;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    private $acao;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    private $escopo;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    private $descricao;

    /**
     *
     * @var int
     * @Column(type="int", length=11, nullable=false, default=0)
     */
    private $id_escopo;

    /**
     *
     * @var int
     * @Column(type="int", length=11, nullable=false, default=0)
     */
    private $id_usuario;

    /**
     * @var int
     * @Column(type="timestamp", nullable=false, default=CURRENT_TIMESTAMP)
     */
    private $updated_at;

    /**
     * @var string
     */
    const ACAO_ADD = 'add';

    /**
     * @var string
     */
    const ACAO_REMOVE = 'remove';

    /**
     * @var string
     */
    const ESCOPO_ITEM = 'item';

    /**
     * @var string
     */
    const ESCOPO_CATEGORIA = 'categoria';

    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Add ação
     *
     * @param $acao
     * @param $escopo
     * @param $descricao
     * @param $id_escopo
     * @param $id_usuario
     * @return bool
     */
    public static function add($acao, $escopo, $descricao, $id_escopo, $id_usuario)
    {
        $entity = new LogProdutoCategoria();

        $entity->acao = $acao;
        $entity->escopo = $escopo;
        $entity->descricao = $descricao;
        $entity->id_escopo = $id_escopo;
        $entity->id_usuario = $id_usuario;

        return $entity->save();
    }
}
