<?php

use Phalcon\Mvc\Model;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class ProdutoCategoriaEstrutura extends Model
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
     * @Column(type="string", nullable=true)
     */
    public $descricao;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */

    public $content;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $parent;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $link;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $father;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $tree;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $last_children;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $children;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $breadcrumb;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $old_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('produto_categoria_estrutura');
        $this->hasMany('id', 'ProdutoCategoriaItem', 'id_produto_categoria_item', ['alias' => 'ProdutoCategoriaItem']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'produto_categoria_estrutura';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Model
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Find all available sorted
     *
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function findAllAvailable()
    {
        return self::find([
            'conditions' => 'id > 1 and status = 1',
            'order' => 'parent ASC, nome ASC'
        ]);
    }

    /**
     * Count
     *
     * @param null|array $parameters
     * @return mixed
     */
    public static function count($parameters = null)
    {
        return parent::count($parameters);
    }

    /**
     * Get category and decode json columns
     *
     * @param int $id
     * @return object
     */
    public static function getJsonDecodeById($id)
    {
        $category = self::findFirst($id);
        if (!$category) {
            return null;
        }

        $category->father = json_decode($category->father);
        $category->tree = json_decode($category->tree);
        $category->last_children = json_decode($category->last_children);
        $category->children = json_decode($category->children);

        return $category;
    }
}
