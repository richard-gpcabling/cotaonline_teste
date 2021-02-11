<?php

namespace App\Services;

use App\Forms\ProdutoCategoriaEstruturaForm;
use App\Helpers\UtilHelper;
use ClienteFaturamento;
use ClienteMarkUp;
use Exception;
use LogProdutoCategoria;
use Phalcon\Di;
use ProdutoCategoriaEstrutura;
use ProdutoCategoriaItem;

class ProdutoCategoriaEstruturaService
{
    /**
     * @var \ProdutoCategoriaEstrutura|\Phalcon\Mvc\Model
     */
    private $model;

    /**
     * @var \Phalcon\DiInterface
     */
    private $di;

    public function __construct()
    {
        $this->model = new ProdutoCategoriaEstrutura();
        $this->di = Di\FactoryDefault::getDefault();
    }

    /**
     * Add category on multiples parents
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
            $ids[] = $this->add($post['nome'], $category, $id_usuario);
        }
        return ['status' => 'success', 'ids' => $ids, 'messages' => ['Categoria inserida com sucesso']];
    }

    /**
     * Add
     *
     * @param string $nome
     * @param int    $parent
     * @param int    $id_usuario
     * @return int
     */
    public function add($nome, $parent, $id_usuario)
    {
        $model = new ProdutoCategoriaEstrutura();
        $ok = $model->save([
            'nome' => trim($nome),
            'parent' => (int) $parent,
            'status' => 1
        ]);

        if (!$ok) {
            return 0;
        }

        // Log
        LogProdutoCategoria::add(
            LogProdutoCategoria::ACAO_ADD,
            LogProdutoCategoria::ESCOPO_CATEGORIA,
            json_encode(['id' => $model->id, 'nome' => $model->nome, 'parent' => $model->parent]),
            $model->id,
            $id_usuario
        );

        // Generate tree
        if ($model->parent === 1) {
            $fatherId = $model->id;
        } else {
            $father = ProdutoCategoriaEstrutura::findFirst("id = $parent");
            $breadcrumb = json_decode($father->breadcrumb);
            $fatherId = isset($breadcrumb[0]->id) ? (int) $breadcrumb[0]->id : $father->id;
        }

        $allCategories = ProdutoCategoriaEstrutura::findAllAvailable();

        // Save tree for father category
        $this->saveTree($fatherId, $allCategories);

        $tree = $this->getTreeFromListById($allCategories, $fatherId);
        foreach ($tree as $itemTree) {
            $this->saveTree($itemTree->id, $allCategories);
        }
        return (int) $model->id;
    }

    /**
     * Remove and generate new tree
     *
     * @param int $id
     * @param int $id_usuario
     * @return bool
     */
    public function remove($id, $id_usuario)
    {
        $category = ProdutoCategoriaEstrutura::findFirst($id);
        if (is_null($category)) {
            return false;
        }

        // Disable children categories
        $tree = json_decode($category->tree);
        if (is_array($tree) && count($tree) > 0) {
            foreach ($tree as $itemTree) {
                $item = ProdutoCategoriaEstrutura::findFirst($itemTree->id);
                if (is_null($item)) {
                    continue;
                }

                // Disable
                $item->link = null;
                $item->father = null;
                $item->tree = null;
                $item->last_children = null;
                $item->children = null;
                $item->breadcrumb = null;
                $item->status = 0;
                $item->save();

                ProdutoCategoriaItem::removeByIdProdutoCategoriaEstrutura($item->id);
            }
        }

        // Get father category before update
        $breadcrumb = json_decode($category->breadcrumb);
        $tree = json_decode($category->tree);

        // Disable this category
        $category->link = null;
        $category->father = null;
        $category->tree = null;
        $category->last_children = null;
        $category->children = null;
        $category->breadcrumb = null;
        $category->status = 0;
        $category->save();
        ProdutoCategoriaItem::removeByIdProdutoCategoriaEstrutura($category->id);

        // Log
        LogProdutoCategoria::add(
            LogProdutoCategoria::ACAO_REMOVE,
            LogProdutoCategoria::ESCOPO_CATEGORIA,
            json_encode(['id' => $category->id, 'nome' => $category->nome, 'parent' => $category->parent]),
            $category->id,
            $id_usuario
        );

        // Father category, inactive all children
        if ((int) $category->parent === 1) {
            foreach ($tree as $item) {
                $treeCategory = ProdutoCategoriaEstrutura::findFirst($item->id);
                if (!$treeCategory) {
                    continue;
                }

                $treeCategory->link = null;
                $treeCategory->father = null;
                $treeCategory->tree = null;
                $treeCategory->last_children = null;
                $treeCategory->children = null;
                $treeCategory->breadcrumb = null;
                $treeCategory->status = 0;
                $treeCategory->save();
            }
            return true;
        }

        $fatherCategoryId = isset($breadcrumb[0]->id) ? (int) $breadcrumb[0]->id : 0;

        // Get the Father Category (First level)
        // And generate new tree for all of children categories
        if ($fatherCategoryId > 0) {
            $categoryFirstLevel = ProdutoCategoriaEstrutura::findFirst($fatherCategoryId);
            if (is_null($categoryFirstLevel)) {
                return true;
            }

            $allCategories = ProdutoCategoriaEstrutura::findAllAvailable();
            $this->saveTree($categoryFirstLevel->id, $allCategories);

            $treeFather = $this->getTreeFromListById($allCategories, $categoryFirstLevel->id);
            foreach ($treeFather as $item) {
                $this->saveTree($item->id, $allCategories);
            }
        }

        return true;
    }

    /**
     * Get limbo
     *
     * @return array
     */
    public function getAdminList()
    {
        $firstLevelCategories = ProdutoCategoriaEstrutura::find(['conditions' => 'parent = 1 and status = 1'])->toArray();

        $categories = [];

        foreach ($firstLevelCategories as $firstLevelCategory) {
            $tree = json_decode($firstLevelCategory['tree']);

            $firstLevel = $this->getFewColumnsFromObjectItem((object) $firstLevelCategory);

            // First level categories
            //$categories[] = $firstLevel;

            $data = new \stdClass();
            $data->id = $firstLevel->id;
            $data->nome = $firstLevel->nome;
            $data->nome_breadcrumb = $firstLevel->nome;
            $data->parent = $firstLevel->parent;
            $data->link = $firstLevel->link;

            $categories[] = $data;

            // Loop at tree and generate a breadcrumb of each item
            foreach ($tree as $itemTree) {
                $breadcrumb = $this->getBreadcrumbFromListById($tree, $itemTree->id);

                // Create structure of name (cat-01 -> cat-02)
                $names = [];
                $names[] = $firstLevel->nome;
                foreach ($breadcrumb as $itemBreadcrumb) {
                    $names[] = $itemBreadcrumb->nome;
                }

                $data = new \stdClass();
                $data->id = $itemTree->id;
                $data->nome = $itemTree->nome;
                $data->nome_breadcrumb = implode('&nbsp;<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> ', $names);
                $data->parent = $itemTree->parent;
                $data->link = $itemTree->link;

                $categories[] = $data;
            }
        }

        return $categories;
    }

    /**
     * Get page listing
     *
     * @param int          $catid
     * @param null|string $filters
     * @param null|int    $manufacturer
     *
     * @return array|null
     */
    public function getPageList($catid, $filters = null, $manufacturer = null)
    {
        $session = $this->di->getSession();
        $request = $this->di->getRequest();

        $numberPage = $request->getQuery('page', 'int');
        $page = (int) $numberPage < 1 ? 1 : $numberPage;

        // Get category
        $category = ProdutoCategoriaEstrutura::getJsonDecodeById($catid);
        if (is_null($category)) {
            return null;
        }

        $listSubCategories = [];
        $listFilters = [];
        $splitFilters = [];
        if (!is_null($filters) && $filters !== '0') {
            $splitFilters = array_map(function ($filter) {
                return (int) $filter;
            }, explode(',', $filters));

            // Get children for filters
            foreach ($splitFilters as $filterId) {
                if (is_null($this->getFromListById($category->tree, $filterId))) {
                    return null;
                }

                // Get children
                $findListFilters = $this->getFirstChildrenFromListByParent($category->tree, $filterId);

                // If children does not exists, it's the last level
                // Just ignore and keeping executing
                if (count($findListFilters) < 1) {
                    continue;
                }

                $listFilter = [];
                // Set selected
                $hasSelection = false;
                foreach ($findListFilters as $key => $filter) {
                    $filter->selected = in_array((int) $filter->id, $splitFilters);
                    if ($filter->selected) {
                        $hasSelection = true;
                    }
                    $listFilter[$key] = $filter;
                }
                $filters = new \stdClass();
                $filters->father = $this->getFromListById($category->tree, $filterId);
                $filters->items = $listFilter;
                $filters->has_selection = $hasSelection;
                $listFilters[] = $filters;
            }
        }

        // Set selected
        $hasSelection = false;
        foreach ($category->children as $key => $subcategory) {
            $subcategory->selected = in_array((int) $subcategory->id, $splitFilters);
            if ($subcategory->selected) {
                $hasSelection = true;
            }
            $listSubCategories[$key] = $subcategory;
        }
        $subcategories = new \stdClass();
        $subcategories->items = $listSubCategories;
        $subcategories->has_selection = $hasSelection;


        // Get all children categories
        $childrenIds = $this->getLastIdsForProductList($category, $splitFilters);

        // Html Schema schema.org
        $schema = $this->getHtmlSchemaData($category, $splitFilters, $manufacturer);

        // Products
        $productItemService = new ProdutoCategoriaItemService();
        $products = $productItemService->getPageList(
            $childrenIds,
            $manufacturer,
            'product',
            $page,
            /*$tabela_fuse,
            $empresa,
            $empresa_tipo,
            $user_status,*/
            $session->auth['custo_pagina']
            /*$markup_fuse,
            $markup_fuse_sf*/
        );

        // Get Manufacturers
        $manufacturers = $productItemService->getPageList($childrenIds, $manufacturer, 'fabricantes');

        $data['manufacturer'] = $manufacturer;
        $data['manufacturers'] = $manufacturers;
        $data['subcategories'] = $subcategories;
        $data['filters'] = $listFilters;
        $data['category'] = $category;
        $data['show_price'] = !empty($cliente_estado);
        $data['page'] = $products;
        $data['schema'] = $schema;

        return $data;
    }

    /**
     * Generate tree
     *
     * @return void
     */
    public function generateTree()
    {
        try {
            $allCategories = ProdutoCategoriaEstrutura::findAllAvailable();
            $listCategories = ProdutoCategoriaEstrutura::findAllAvailable();

            echo 'Starting in ' . date('H:i:s', time()) . '<br/>';

            foreach ($listCategories as $category) {
                $this->saveTree($category->id, $allCategories);
                echo 'Generated tree of ' . $category->nome . '<br/>';
            }
            
            echo 'Finished in ' . date('H:i:s', time()) . '<br/>';
            echo 'Executed successfully';
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        exit;
    }

    /**
     * Save tree
     *
     * @param int  $id
     * @param null $allCategories
     */
    public function saveTree($id, $allCategories = null)
    {
        if (is_null($allCategories)) {
            $allCategories = ProdutoCategoriaEstrutura::findAllAvailable();
        }

        $category = ProdutoCategoriaEstrutura::findFirst((int) $id);
        if (!$category) {
            return ;
        }

        // Get father
        $father = ProdutoCategoriaEstrutura::findFirst((int) $category->parent);
        if (!$father) {
            return ;
        }

        $breadcrumb = $this->getBreadcrumbFromListById($allCategories, $category->id);
        
        $category->link = $this->generateLinkFromBreadcrumb($breadcrumb);
        $category->father = json_encode($this->getFewColumnsFromObjectItem($father), JSON_UNESCAPED_UNICODE);
        $category->tree = json_encode($this->getTreeFromListById($allCategories, $category->id), JSON_UNESCAPED_UNICODE);
        $category->last_children = json_encode($this->getLastChildrenFromListById($allCategories, $category->id), JSON_UNESCAPED_UNICODE);
        $category->children = json_encode($this->getFirstChildrenFromListByParent($allCategories, $category->id), JSON_UNESCAPED_UNICODE);
        $category->breadcrumb = json_encode($breadcrumb, JSON_UNESCAPED_UNICODE);
        $category->save();

        // Generate links for items tree
        $this->generateLinksForItemTree($category, $allCategories);
    }

    /**
     *
     * @param $category
     * @param $allCategories
     */
    private function generateLinksForItemTree($category, $allCategories)
    {
        $breadcrumb = json_decode($category->breadcrumb);
        $father = json_decode($category->father);
        $tree = json_decode($category->tree);
        $last_children = json_decode($category->last_children);
        $children = json_decode($category->children);

        $father->link = $this->generateLinkFromBreadcrumb($this->getBreadcrumbFromListById($allCategories, $father->id));

        $newTree = [];
        foreach ($tree as $itemTree) {
            $itemTreeBreadcrumb = $this->getBreadcrumbFromListById($allCategories, $itemTree->id);
            $itemTree->link = $this->generateLinkFromBreadcrumb($itemTreeBreadcrumb);
            $newTree[] = $itemTree;
        }

        $newLastChildren = [];
        foreach ($last_children as $itemLastChild) {
            $itemLastChildBreadcrumb = $this->getBreadcrumbFromListById($allCategories, $itemLastChild->id);
            $itemLastChild->link = $this->generateLinkFromBreadcrumb($itemLastChildBreadcrumb);
            $newLastChildren[] = $itemLastChild;
        }

        $newChildren = [];
        foreach ($children as $itemChild) {
            $itemChildBreadcrumb = $this->getBreadcrumbFromListById($allCategories, $itemChild->id);
            $itemChild->link = $this->generateLinkFromBreadcrumb($itemChildBreadcrumb);
            $newChildren[] = $itemChild;
        }

        $category->link = $this->generateLinkFromBreadcrumb($breadcrumb);
        $category->father = json_encode($father, JSON_UNESCAPED_UNICODE);
        $category->tree = json_encode($newTree, JSON_UNESCAPED_UNICODE);
        $category->last_children = json_encode($newLastChildren, JSON_UNESCAPED_UNICODE);
        $category->children = json_encode($newChildren, JSON_UNESCAPED_UNICODE);

        $category->save();
    }

    /**
     * Get last ids for product list
     *
     * @param object $mainCategory
     * @param array  $filters
     * @return array
     */
    private function getLastIdsForProductList($mainCategory, $filters)
    {
        // When no filter is selected
        if (!is_array($filters) || count($filters) < 1) {
            $ids = $this->getIdsFromList($mainCategory->tree);
            $ids[] = $mainCategory->id;
            return $ids;
        }

        // Get list from selected filters
        $allListFilters = [];
        foreach ($filters as $filterId) {
            $allListFilters[] = $this->getFromListById($mainCategory->tree, $filterId);
        }

        // Remove existing parent
        $listFilters = [];
        foreach ($allListFilters as $filter) {
            $exists = false;
            foreach ($allListFilters as $filter2) {
                if ($filter->id === $filter2->parent) {
                    $exists = true;
                }
            }
            if (!$exists) {
                $listFilters[] = $filter;
            }
        }

        $ids = [];
        foreach ($listFilters as $filter) {
            $filterTree = $this->getTreeFromListById($mainCategory->tree, $filter->id);

            // Self category
            $ids[] = $filter->id;

            // There is no children, just concatenate
            if (count($filterTree) < 1) {
                continue;
            }

            $filterIds = $this->getIdsFromList($filterTree);
            foreach ($filterIds as $filterId) {
                if (!in_array($filterId, $ids)) {
                    $ids[] = $filterId;
                }
            }
        }

        return $ids;
    }

    /**
     * Get all children
     *
     * @param $categories
     *
     * @return array
     */
    private function getIdsFromList($categories)
    {
        $ids = [];
        foreach ($categories as $category) {
            if (!isset($category->id)) {
                continue;
            }
            $ids[] = $category->id;
        }
        return $ids;
    }

    /**
     * Get all categories (Children, Grandchild, ...)
     *
     * @param array  $categories
     * @param int    $id
     * @param array  $data
     *
     * @return array
     */
    private function getTreeFromListById($categories, $id, $data = [])
    {
        $items = $this->getFirstChildrenFromListByParent($categories, $id);
        if (count($items)) {
            $data = array_merge($data, $items);
            foreach ($items as $item) {
                $data = $this->getTreeFromListById($categories, $item->id, $data);
            }
        }
        return $data;
    }

    /**
     * Get first children
     *
     * @param array $categories
     * @param int   $parent
     *
     * @return array
     */
    private function getFirstChildrenFromListByParent($categories, $parent)
    {
        $items = [];
        foreach ($categories as $item) {
            if ((int) $item->parent === (int) $parent) {
                $items[] = $this->getFewColumnsFromObjectItem($item);
            }
        }
        return $items;
    }

    /**
     * Get last children (Last level)
     *
     * @param array $categories
     * @param int   $id
     * @param array $children
     *
     * @return array
     */
    private function getLastChildrenFromListById($categories, $id, $children = [])
    {
        $items = $this->getFirstChildrenFromListByParent($categories, $id);
        if (count($items) < 1) {
            $category = $this->getFromListById($categories, $id);
            $children[] = $this->getFewColumnsFromObjectItem($category);
            return $children;
        }

        foreach ($items as $item) {
            $child = $this->getFirstChildFromListByParent($categories, $item->id);
            if (!is_null($child)) {
                $children = $this->getLastChildrenFromListById($categories, $child->id, $children);
            } else {
                $children[] = $item;
            }
        }

        return $children;
    }

    /**
     * @param array $categories
     * @param int   $id
     *
     * @return object|null
     */
    private function getFromListById($categories, $id)
    {
        $item = null;
        foreach ($categories as $category) {
            if ((int) $category->id !== (int) $id) {
                continue;
            }
            $item = $category;
            break;
        }
        return $item;
    }

    /**
     * Get first child category
     *
     * @param array $categories
     * @param int   $parent
     *
     * @return object|null
     */
    private function getFirstChildFromListByParent($categories, $parent)
    {
        $child = null;
        foreach ($categories as $item) {
            if ((int) $item->parent === (int) $parent) {
                $child = $item;
                break;
            }
        }
        return $child;
    }

    /**
     *
     * @param array $allCategories
     * @param int   $id
     * @return array
     */
    private function getBreadcrumbFromListById($allCategories, $id)
    {
        $breadcrumb = $this->getBreadcrumbFromList($allCategories, $id);
        return $this->generateLinksForBreadcrumb(array_reverse($breadcrumb));
    }

    /**
     * @param array $allCategories
     * @param int   $parent
     * @param array $breadcrumb
     * @return array
     */
    private function getBreadcrumbFromList($allCategories, $parent, $breadcrumb = [])
    {
        foreach ($allCategories as $category) {
            if ((int) $category->id === (int) $parent) {
                $breadcrumb[] = $this->getFewColumnsFromObjectItem($category);
                $breadcrumb = $this->getBreadcrumbFromList($allCategories, $category->parent, $breadcrumb);
            }
        }
        return $breadcrumb;
    }

    /**
     * Generate links for breadcrumb list
     *
     * @param array $breadcrumb
     * @return array
     */
    private function generateLinksForBreadcrumb($breadcrumb)
    {
        $result = [];

        $mainId = 0;
        $parentIds = [];
        foreach ($breadcrumb as $key => $item) {
            if ($key !== 0) {
                $parentIds[] = $item->id;
                $link = '/produto/category/' . $mainId . '/' . implode(',', $parentIds);
            } else {
                $mainId = $item->id;
                $link = '/produto/category/' . $mainId;
            }

            $item->link = $link;
            $result[] = $item;
        }
        return $result;
    }

    /**
     * Generate link for breadcrumb
     *
     * @param array $breadcrumb
     * @return string
     */
    public function generateLinkFromBreadcrumb($breadcrumb)
    {
        $link = '/produto/category/';

        $mainId = 0;
        $filterIds = [];
        foreach ($breadcrumb as $key => $item) {
            if ($key !== 0) {
                $filterIds[] = (int) $item->id;
            } else {
                $mainId = (int) $item->id;
            }
        }

        $link .= $mainId;
        if (count($filterIds) > 0) {
            $link .= '/' . implode(',', $filterIds);
        }
        return $link;
    }

    /**
     * Get few columns from object item
     *
     * @param object $categoryItem
     * @return \\stdClass
     */
    private function getFewColumnsFromObjectItem($categoryItem)
    {
        return UtilHelper::extractColumnsFromObject($categoryItem, ['id', 'nome', 'parent', 'link']);
    }

    /**
     * Validate admin add
     *
     * @param array $post
     * @return array
     */
    private function isAddAdminFormValid($post)
    {
        $entity = UtilHelper::assignEntity(new ProdutoCategoriaEstrutura(), $post);

        $form = new ProdutoCategoriaEstruturaForm($entity, ['add_admin' => true]);
        if (!$form->isValid($post)) {
            return [
                'status' => 'error',
                'form' => $form,
                'messages' => UtilHelper::getValidatorMessages($form->getMessages())
            ];
        }
        return ['status' => 'success', 'messages' => ['Dados válidos']];
    }

    /**
     * Schema breadcrumb
     *
     * @param object $category
     * @param array  $filters
     * @param string $manufacturer
     * @see https://schema.org/BreadcrumbList
     * @return \\stdClass
     */
    private function getHtmlSchemaData($category, array $filters, $manufacturer)
    {
        $breadcrumb = [];
        $titles = [];

        $baseBreadcrumbUrl = '/produto/category/' . $category->id;

        $data = new \stdClass();
        $data->id = $category->id;
        $data->nome = $category->nome;
        $data->link = $baseBreadcrumbUrl;
        $breadcrumb[] = $data;

        // Base title
        $titles[] = $category->nome;

        $filterBefore = [];
        foreach ($filters as $filterId) {
            $filterBefore[] = $filterId;

            $filter = $this->getFromListById($category->tree, $filterId);

            $filter->link = $baseBreadcrumbUrl . '/' . implode(',', $filterBefore);
            $breadcrumb[] = $filter;

            $titles[] = $filter->nome;
        }

        if (!empty($manufacturer) && $manufacturer !== '0') {
            $titles[] = $manufacturer;
        }

        $jsonLd = ['@context' => 'http://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => []];
        foreach ($breadcrumb as $key => $item) {
            $jsonLd['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => ++$key,
                'item' => [
                    '@id' => $item->link,
                    'name' => $item->nome
                ]
            ];
        }

        $data = new \stdClass();
        $data->breadcrumb = $breadcrumb;
        $data->titles = $titles;
        $data->jsonld = $jsonLd;

        return $data;
    }
}
