<?php

namespace App\Services;

use App\Forms\ProdutoCategoriaItemForm;
use App\Services\ProdutoCoreService;
use App\Helpers\UtilHelper;
use Phalcon\Di;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;
use ProdutoCategoriaItem;
use ProdutoDescricao;
use ProdutoMarkUp;

class ProdutoCategoriaItemService
{
    private $model;

    private $di;

    public function __construct()
    {
        $this->model = new ProdutoCategoriaItem();
        $this->di = Di\FactoryDefault::getDefault();
    }

    /**
     * @param $categories
     * @param $manufacturer
     * @param $type
     * @param $page
     * @param $tabela_fuse
     * @param $empresa
     * @param $empresa_tipo
     * @param $user_status
     * @param $custo_pagina
     * @param $markup_fuse
     * @param $markup_fuse_sf
     * @return \stdClass
     */
    public function getPageList(
        $categories,
        $manufacturer,
        $type,
        $page = 1,
        $tabela_fuse = '',
        $empresa = 0,
        $empresa_tipo = 0,
        $user_status = 0,
        $custo_pagina = 0,
        $markup_fuse = '',
        $markup_fuse_sf = ''
    ) {
        $categoriesIn = implode(',', $categories);

        $session = $this->di->getSession();

        $builder = $this->di->get('modelsManager')
            ->createBuilder()
            ->from('ProdutoCore')
            ->join('ProdutoCategoriaItem', 'ProdutoCore.codigo_produto = ProdutoCategoriaItem.codigo_produto')
            ->join('ProdutoFabricante', 'ProdutoFabricante.sigla = ProdutoCore.sigla_fabricante');

        // Where
        $where = "ProdutoCategoriaItem.id_produto_categoria_estrutura IN($categoriesIn)";

        // Get Products
        if ($type === 'product') {
            // Manufacturer filter is only for product query
            if (!is_null($manufacturer) && $manufacturer !== '0') {
                $where .= " and ProdutoFabricante.sigla = '$manufacturer'";
            }

            $builder->columns('ProdutoCore.*, ProdutoFabricante.nome as nome_fabricante, ProdutoEstoque.*,ProdutoEstoque.total_estoque as total_estoque')
                ->join('ProdutoEstoque', 'ProdutoCore.codigo_produto = ProdutoEstoque.codigo_produto')
                ->where($where)
                ->groupBy('ProdutoCore.id')
                ->orderBy('total_estoque DESC');

            $paginator = new Paginator([
                'builder' => $builder,
                'limit' => 50,
                'page' => $page
            ]);

            $paginate = $paginator->getPaginate();

            $products = $paginate->items->toArray();

            $listProducts = [];
            foreach ($products as $product) {
                $productCore = $product->produtoCore;
                $productCore->nome_fabricante = $product->nome_fabricante;
                $productCore->estoque = $product->produtoCore;
                $productCore->estoque->total_estoque = $product->total_estoque;

                // Get description
                $productCore->description = ProdutoDescricao::getMainImageByCodigoProduto($productCore->codigo_produto);

                $price = new ProdutoCoreService;
                $price = $price->formaPreco($session->auth['id'],$productCore->codigo_produto);
                

                $productCore->price = $price->price;
                $productCore->origem = $price->origem;
                $productCore->icms = ($price->taxas['c_icms']!='NULL') ? $price->taxas['c_icms'] : 0;
                $listProducts[] = $productCore;
            }

            $paginate->items = $listProducts;

            return $paginate;
        } else {
            // Get Manufacturers
            $builder->columns('ProdutoFabricante.*')
                ->where($where)
                ->groupBy('ProdutoFabricante.id')
                ->orderBy('ProdutoFabricante.nome');

            return $builder->getQuery()->execute();
        }
    }

    /**
     * Get admin edit list
     *
     * @param int $codigo_produto
     * @return array
     */
    public function getAdminListEdit($codigo_produto)
    {
        $estruturaService = new ProdutoCategoriaEstruturaService();
        $categories = $estruturaService->getAdminList();
        $result = [];
        $productCategories = ProdutoCategoriaItem::findByCodigoProduto($codigo_produto);

        foreach ($categories as $category) {
            $checked = false;
            foreach ($productCategories as $productCategory) {
                if ((int) $productCategory->id_produto_categoria_estrutura === (int) $category->id) {
                    $checked = true;
                    break;
                }
            }
            $category->checked = $checked;
            $result[] = $category;
        }
        return $result;
    }
    /**
     * Add categories to product
     *
     * @param array $post
     * @param int   $id_usuario
     * @return array
     */
    public function addAdmin($post, $id_usuario)
    {
        // Validation
        $result = $this->isAddAdminFormValid($post);
        if ($result['status'] !== 'success') {
            return $result;
        }

        // Add
        $ids = [];
        foreach ($post['categories'] as $category) {
            $ids[] = ProdutoCategoriaItem::add($post['codigo_produto'], $category, $id_usuario);
        }

        // Remove product from categories not in this list
        ProdutoCategoriaItem::deleteNotInCategories($post['codigo_produto'], $post['categories'], $id_usuario);

        return ['status' => 'success', 'ids' => $ids, 'messages' => ['Categoria salva com sucesso']];
    }

    /**
     * Admin validation add
     *
     * @param array $post
     * @return array
     */
    private function isAddAdminFormValid($post)
    {
        $entity = UtilHelper::assignEntity(new ProdutoCategoriaItem(), $post);

        $form = new ProdutoCategoriaItemForm($entity, ['add_admin' => true]);
        if (!$form->isValid($post)) {
            return [
                'status' => 'error',
                'form' => $form,
                'messages' => UtilHelper::getValidatorMessages($form->getMessages())
            ];
        }
        return ['status' => 'success', 'messages' => ['Dados válidos']];
    }
}
