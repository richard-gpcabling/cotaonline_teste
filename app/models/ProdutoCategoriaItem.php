<?php

use Phalcon\Db\Column;

class ProdutoCategoriaItem extends \Phalcon\Mvc\Model
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
     * @Column(type="integer", length=11, nullable=true)
     */
    public $codigo_produto;
    
    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $id_produto_categoria_estrutura;

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
        $this->belongsTo('categoria_estrutura', '\ProdutoCategoriaEstrutura', 'id', ['alias' => 'ProdutoCategoriaEstrutura']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'produto_categoria_item';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoFamiliaEstrutura[]|ProdutoFamiliaEstrutura
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoFamiliaEstrutura
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Count records
     *
     * @param null|array $parameters
     * @return mixed
     */
    public static function count($parameters = null)
    {
        return parent::count($parameters);
    }

    /**
     * Add product
     *
     * @param int $codigo_produto
     * @param int $id_produto_categoria_estrutura
     * @param int $id_usuario
     * @return int
     */
    public static function add(
        $codigo_produto,
        $id_produto_categoria_estrutura,
        $id_usuario
    ) {
        $conditionsCount = "codigo_produto = $codigo_produto and id_produto_categoria_estrutura = $id_produto_categoria_estrutura";
        $count = self::count(['conditions' => $conditionsCount]);

        // Category already joined on product, just update status
        if ($count > 0) {
            $where = "codigo_produto = $codigo_produto and id_produto_categoria_estrutura = $id_produto_categoria_estrutura";
            $data = self::findFirst([
                'conditions' => $where
            ]);

            if ($data) {
                $data->status = 1;
                $data->save();
            }
            return $data->id;
        }

        // Add
        $data = new ProdutoCategoriaItem();
        $saved = $data->save([
            'codigo_produto' => $codigo_produto,
            'id_produto_categoria_estrutura' => (int) $id_produto_categoria_estrutura,
            'status' => 1,
            'old_id' => 0
        ]);

        if (!$saved) {
            return 0;
        }

        // Log
        LogProdutoCategoria::add(
            LogProdutoCategoria::ACAO_ADD,
            LogProdutoCategoria::ESCOPO_ITEM,
            "Added to category $id_produto_categoria_estrutura",
            $codigo_produto,
            $id_usuario
        );

        return $data->id;
    }

    /**
     * Sync products and categories
     *
     * @param int   $codigo_produto
     * @param array $categories
     * @param int   $id_usuario
     * @return bool
     */
    public static function deleteNotInCategories($codigo_produto, $categories, $id_usuario)
    {
        $where = "codigo_produto = $codigo_produto and id_produto_categoria_estrutura not in(" . implode(',', $categories) . ")";
        $rows = self::find(['conditions' => $where]);
        foreach ($rows as $row) {
            $row->delete();

            // Log
            LogProdutoCategoria::add(
                LogProdutoCategoria::ACAO_REMOVE,
                LogProdutoCategoria::ESCOPO_ITEM,
                "Removed from category {$row->id_produto_categoria_estrutura}",
                $codigo_produto,
                $id_usuario
            );
        }
        return true;
    }

    /**
     * Get products without category
     *
     * @return array
     */
    public static function getAdminLimbo()
    {
        $di = Phalcon\Di\FactoryDefault::getDefault();

        $params = [
            "models"     => ["ProdutoCore"],
            "columns"    => ["ProdutoCore.codigo_produto", "ProdutoCore.descricao_sys"],
            "order"      => ["ProdutoCore.descricao_sys"]
        ];

        $builder = $di->getModelsManager()->createBuilder($params);
        $builder->leftJoin("ProdutoCategoriaItem", "ProdutoCore.codigo_produto = ProdutoCategoriaItem.codigo_produto");
        $builder->where("ProdutoCore.status = 1 AND ProdutoCategoriaItem.id IS NULL");

        try {
            return $builder->getQuery()->execute()->toArray();
        } catch (Exception $ex) {
            return [];
        }
    }

    /**
     * Get main product category
     *
     * @param int $codigo_produto
     * @return \stdClass|null
     */
    public static function getMainCategoryByCodigoProduto($codigo_produto)
    {
        /**
         * @var \Phalcon\Db\Adapter\Pdo $db
         */
        $db = Phalcon\Di::getDefault()->get('db');

        $query = "SELECT A.* FROM produto_categoria_estrutura AS A " .
            "INNER JOIN produto_categoria_item AS B " .
            "ON A.id = B.id_produto_categoria_estrutura " .
            "WHERE B.codigo_produto = $codigo_produto " .
            "ORDER BY B.id_produto_categoria_estrutura DESC " .
            "LIMIT 1";

        $category = $db->query($query)->fetch(\Phalcon\Db::FETCH_OBJ);
        if (is_null($category)) {
            return null;
        }

        $data = new stdClass();
        $data->id = $category->id;
        $data->nome = $category->nome;
        $data->breadcrumb = json_decode($category->breadcrumb);

        return $data;
    }

    public static function removeByIdProdutoCategoriaEstrutura($id_produto_categoria_estrutura)
    {
        /**
         * @var \Phalcon\Mvc\Model\Manager $manager
         * @var \Phalcon\Mvc\Model\QueryInterface $result
         */
        $manager = Phalcon\Di\FactoryDefault::getDefault()->getModelsManager();

        $query = "DELETE FROM ProdutoCategoriaItem " .
            "WHERE ProdutoCategoriaItem.id_produto_categoria_estrutura = $id_produto_categoria_estrutura";

        $result = $manager->executeQuery($query);

        return $result->success();
    }
}
