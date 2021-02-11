<?php
use App\Forms\ProdutoCategoriaEstruturaForm;
use App\Forms\ProdutoForm;
use App\Services\LogProdutoViewService;
use App\Services\LogUserByRoleService;
use App\Services\LogSearchQueryService;
use App\Services\ProdutoAdminService;
use App\Services\ProdutoCategoriaEstruturaService;
use App\Services\ProdutoCategoriaItemService;
use App\Services\ProdutoCoreService;
use Phalcon\Di;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;
use App\Helpers\UtilHelper;
use Psy\Exception\ThrowUpException;

class ProdutoController extends ControllerBase
{
    public function initialize(){
        //TODO - Não carregar no produtos/admin

        $title_string= '';

        $codigo_produto = $this->router->getParams();
        
        if(!empty($codigo_produto)):
            if (strlen($codigo_produto[0]) == 5) :
                $find_produto = ProdutoCore::findFirstByCodigoProduto($codigo_produto[0]);
                $descricao_sys = $find_produto->descricao_sys;

                $title_string = ' | ' . $codigo_produto[0] . ' — ' . mb_substr($descricao_sys, 0, 15) . '...';
            endif;

            $delete_security = md5(uniqid(rand(), true));
            $this->persistent->unique = $delete_security;
        endif;

        $this->tag->setTitle('Produtos' . $title_string);
        parent::initialize();

    }

    public function indexAction($codigo_produto = null)
    {
        try {
            if (!$this->request->isPost()) {//$id
                if ($codigo_produto == null) {
                    $products = null;
                    $this->flash->error("Produto inexistente.");
                    return $this->dispatcher->forward(["controller" => "index", "action" => "index",]);
                } else {
                    //Verifica se o produto existe ou envia o usuário de volta à primeira página
                    $produto = ProdutoCore::findFirstByCodigoProduto($codigo_produto);

                    if (!$produto or $produto->status == 0) {
                        $this->flash->error("Produto inexistente.");
                        return $this->dispatcher->forward(["controller" => "index", "action" => "index",]);
                    }

                    $produto_descricao = ProdutoDescricao::findFirstByCodigoProduto($codigo_produto);

                    $tags = ProdutoTags::findFirstByCodigoProduto($codigo_produto);
                    $tags = ($tags) ? $tags->tags : '';

                    $produto_fabricante = ProdutoFabricante::findFirstBySigla($produto->sigla_fabricante);

                    $estoque = ProdutoEstoque::find([
                        'conditions'=> "codigo_produto = '".$codigo_produto."'",
                        'columns'=> 'codigo_produto,total_estoque'
                        ]);
                    /*                     * ***************************** */
                    /* Pega categoria strings se houver */
                    /*                     * ***************************** */
                    /*                     * ***************************** */
                    $categoria = ProdutoCategoriaItem::getMainCategoryByCodigoProduto($codigo_produto);

                    /*                     * ******************* */
                    /* Pega imagens e anexos */
                    /*                     * ******************* */
                    /*                     * ******************* */
                    $image_directory = "../public/produto_imagem/" . $codigo_produto . "/";
                    $image_files = scandir($image_directory);
                    $image_files = array_splice($image_files, 2);

                    $document_directory = "../public/produto_documento/" . $codigo_produto . "/";
                    $document_files = scandir($document_directory);
                    $document_files = array_splice($document_files, 2);

                    if (!isset($image_files)) {
                        $image_files = false;
                    }

                    $this->view->doc = $document_files;
                    $this->view->img = $image_files;

                    /*                     * ******************************** */
                    /* Faz a inserção no log_produto_view */
                    /*                     * ******************************** */
                    /*                     * ******************************** */
                    $date_date = [date('Y'), date('m'), date('d')];
                    $date_date = implode($date_date);

                    $find_log = LogProdutoView::find(["conditions" => "codigo_produto = ?0 AND date=?1", "bind" => [
                                    0 => $codigo_produto,
                                    1 => $date_date,]]);

                    $query_insert = "INSERT INTO LogProdutoView (codigo_produto, view_count, date) VALUES ($codigo_produto, 1,$date_date)";
                    $query_update = "UPDATE LogProdutoView SET view_count=view_count+1 WHERE codigo_produto= $codigo_produto AND date=$date_date";

                    $result_query_insert = $this->modelsManager->createQuery($query_insert);
                    $result_query_update = $this->modelsManager->createQuery($query_update);

                    if ($find_log->count() == 0) {
                        $execute_query_insert = $result_query_insert->execute();
                    } else {
                        $execute_query_update = $result_query_update->execute();
                    }

                    /*                     * ********************************************* */
                    //Faz a inserção no log_produto_usuario_date_view
                    /*                     * ********************************************* */
                    /*                     * ********************************************* */
                    if (isset($this->session->auth) && isset($this->session->auth['id'])) {
                        $user_id = $this->session->auth['id'];
                        $find_log_user = LogProdutoUsuarioDateView::find(["conditions" => "codigo_produto = ?0 AND usuario_id=?1 AND date=?2", "bind" => [
                                        0 => $codigo_produto,
                                        1 => $user_id,
                                        2 => $date_date,]]);

                        $query_insert_user = "INSERT INTO LogProdutoUsuarioDateView (codigo_produto,usuario_id, view_count, date)
                                    VALUES ($codigo_produto,$user_id,1,$date_date)";
                        $query_update_user = "UPDATE LogProdutoUsuarioDateView SET view_count=view_count+1
                                    WHERE codigo_produto= $codigo_produto AND usuario_id=$user_id AND date=$date_date";

                        $result_query_insert_user = $this->modelsManager->createQuery($query_insert_user);
                        $result_query_update_user = $this->modelsManager->createQuery($query_update_user);

                        if ($find_log_user->count() == 0) {
                            $execute_query_insert_user = $result_query_insert_user->execute();
                        } else {
                            $execute_query_update_user = $result_query_update_user->execute();
                        }
                    }

                    /*                     * ************************* */
                    /* Compila o count de produtos */
                    /*                     * ************************* */
                    /*                     * ************************* */
                    $find_log_view = LogProdutoView::find(["conditions" => "codigo_produto = ?0", "bind" => [0 => $codigo_produto,]]);

                    $prod_view_count = 0;
                    foreach ($find_log_view as $key) {
                        $prod_view_count += $key->view_count;
                    }

                    if ($prod_view_count < 2) {
                        $prod_view_count = "Sem visualização";
                    }

                    //$this->view->prod_view_count = $prod_view_count;

                    /*                     * ************************************* */
                    //Compila o count de produtos por usuário
                    //EM DEV
                    /*                     * ************************************* */
                    /*                     * ************************************* */
                    $find_log_user_view = LogProdutoUsuarioDateView::find(["conditions" => "codigo_produto = ?0", "bind" => [0 => $codigo_produto,]]);

                    $price  = new ProdutoCoreService;
                    $price  = $price->formaPreco($user_id,$codigo_produto);
                    $cst    = $price->taxas['cst'];
                    $icms   = ($price->taxas['c_icms']!='NULL') ? $price->taxas['c_icms'] : 0 ;
                    $ipi    = ($price->taxas['c_ipi']!='NULL') ? $price->taxas['c_ipi'] : 0 ;
                    $st     = ($price->taxas['c_st']!='NULL') ? $price->taxas['c_st'] : 0 ;
                    $descricao_site = ($produto->descricao_site == 'NULL') ? $produto->descricao_sys:$produto->descricao_site;

                    $produto_set = [
                        'id' => $produto->id,
                        'codigo_produto' => $codigo_produto,
                        'tags' => trim($tags),
                        'descricao_site' => $descricao_site,
                        'descricao' => $produto_descricao->texto,
                        'categoria' => $categoria,
                        'view_count' => $prod_view_count,
                        'sigla_fabricante' => $produto->sigla_fabricante,
                        'fabricante_nome' => $produto_fabricante->nome,
                        'ref' => $produto->ref,
                        'unidade_venda' => $produto->unidade_venda,
                        'moeda_de_venda' => $produto->moeda_venda,
                        'tipo_fiscal' => $produto->tipo_fiscal,
                        'ncm' => $produto->ncm,
                        'total_estoque' => number_format($estoque[0]->total_estoque,0,"","."),
                        'preco' => $price->price,
                        'origem' => $price->origem,
                        'cst' => $cst,
                        'icms' => $icms,
                        'ipi' => $ipi,
                        'st' => $st
                    ];

                    $impostos_fixos = new stdClass;
                    $impostos_fixos->pis = ProdutoImpostoFixo::findFirstByNome('pis');
                    $impostos_fixos->pis = str_replace('.',',',$impostos_fixos->pis->total);
                    $impostos_fixos->cofins = ProdutoImpostoFixo::findFirstByNome('cofins');
                    $impostos_fixos->cofins = str_replace('.',',',$impostos_fixos->cofins->total);
                    $impostos_fixos->ircsll = ProdutoImpostoFixo::findFirstByNome('ir/csll');
                    $impostos_fixos->ircsll = str_replace('.',',',$impostos_fixos->ircsll->total);

                    $produto_set = (object) $produto_set;

                    $this->view->setVar('produto', $produto_set);
                    $this->view->impostos_fixos = $impostos_fixos;
                }
            }
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
        }
    }

    public function categoryAction($id = null, $filters = null, $manufacturer = null)
    {
        $this->searchCategoryAction($id, $filters, $manufacturer);
    }

    public function subcategory1Action($id)
    {
        $this->searchCategoryAction($id, 2);
    }

    public function subcategory2Action($id)
    {
        $this->searchCategoryAction($id, 3);
    }

    public function subcategory3Action($id)
    {
        $this->searchCategoryAction($id, 4);
    }

    public function searchCategoryAction($catid = null, $filters = null, $manufacturer = null)
    {
        try {
            $service = new ProdutoCategoriaEstruturaService();
            $data = $service->getPageList($catid, $filters, $manufacturer);
            $this->view->manufacturer = $data['manufacturer'];
            $this->view->manufacturers = $data['manufacturers'];
            $this->view->subcategories = $data['subcategories'];
            $this->view->filters = $data['filters'];
            $this->view->category = $data['category'];
            $this->view->cliente_codigo_policom = $data['cliente_codigo_policom'];
            $this->view->cliente_estado = $data['cliente_estado'];
            $this->view->tabeladecustoRaw = $data['tabeladecustoRaw'];
            $this->view->cliente_tipo = $data['cliente_tipo'];
            $this->view->markup = $data['markup'];
            $this->view->page = $data['page'];
            $this->view->schema = $data['schema'];
            $this->view->show_price = $data['show_price'];

            $this->tag->setTitle('Produtos ' . implode(', ' , $data['schema']->titles));

            $this->view->produto_documents = ProdutoCoreService::getDocuments(
                $data["page"]->items
            );

            $this->view->pick('produto/categorySearch');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
        }
    }

    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "ProdutoCore", $this->request->getPost());
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = array();
        if ($this->persistent->searchParams && !$this->persistent->resetSearchParams) {
            $parameters = $this->persistent->searchParams;
        }

        $produto = ProdutoCore::find($parameters);
        $this->persistent->resetSearchParams = false;
        if (count($produto) == 0) {
            $this->flash->notice("A busca não encontrou produtos.");
        }
        $this->view->pick('produto/index');

        $paginator = new Paginator(array(
            "data" => $produto,
            "limit" => 50,
            "page" => $numberPage
        ));
        $this->view->form = new ProdutoForm;
        $this->view->page = $paginator->getPaginate();
        $this->persistent->searchParams = [];
    }

    public function newAction()
    {
        $service = new ProdutoCategoriaEstruturaService();

        $this->view->categories = $service->getAdminList();
        $this->view->form = new ProdutoForm(null, array('edit' => false, 'create' => true));
    }

    public function customSearchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "ProdutoCore", $this->request->getPost());
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $searchquery = '';

        try {
            if (!$this->request->isPost()) {

                $searchquery = $this->request->getQuery('searchquery');
                $fabr = $this->request->getQuery('fabr');
                $ap1=$searchquery-10;
                $ap2=$searchquery+10;

                $produtos = $this->di->getModelsManager()
                            ->createBuilder()
                            ->columns('ProdutoCore.descricao_site as descricao_site,
                            ProdutoCore.promo as promo,
                            ProdutoCore.descricao_sys as descricao_sys,
                            ProdutoCore.codigo_produto as codigo_produto,
                            ProdutoCore.ref as ref,
                            ProdutoCore.unidade_venda as unidade_venda,
                            ProdutoCore.tipo_fiscal as tipo_fiscal,
                            ProdutoEstoque.total_estoque as total_estoque,
                            ProdutoFabricante.nome as fabricante_nome,
                            ProdutoCore.sigla_fabricante
                            ')
                            ->from('ProdutoCore')
                            ->join('ProdutoDescricao','ProdutoCore.codigo_produto = ProdutoDescricao.codigo_produto')
                            ->join('ProdutoEstoque','ProdutoCore.codigo_produto = ProdutoEstoque.codigo_produto')
                            ->join('ProdutoFabricante','ProdutoCore.sigla_fabricante = ProdutoFabricante.sigla')
                            ->betweenWhere("ProdutoCore.codigo_produto", $ap1, $ap2)
                            ->orwhere('ProdutoCore.codigo_produto LIKE :searchquery:
                            OR ProdutoCore.descricao_site like :searchquery:
                            OR ProdutoCore.ref like :searchquery:
                            OR ProdutoDescricao.texto like :searchquery:',["searchquery"=>'%'.$searchquery.'%'])
                            ->andwhere('ProdutoCore.status = 1');

                            if(!empty($fabr)):
                                $produtos->andwhere('ProdutoCore.sigla_fabricante = :fabr:',["fabr"=>$fabr]);
                            endif;
                            $produtos->groupby('ProdutoCore.codigo_produto')
                            ->orderby('ProdutoEstoque.total_estoque DESC,ProdutoCore.tipo_fiscal DESC');
            }
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
        }

        $paginator = new Paginator([
            "builder" => $produtos,
            "limit" => 50,
            "page" => $numberPage
        ]);

        
        $page = $paginator->getPaginate();
        $formaPreco = new ProdutoCoreService;
        $price=[];
        $products = [];
        foreach($page->items as $item):
            $products[] = $item;
            $price[$item->codigo_produto] = $formaPreco->formaPreco($this->session->auth['id'],$item->codigo_produto)->price;
            $origem[$item->codigo_produto] = $formaPreco->formaPreco($this->session->auth['id'],$item->codigo_produto)->origem;
            $icms[$item->codigo_produto] = $formaPreco->formaPreco($this->session->auth['id'],$item->codigo_produto)->taxas["c_icms"];
            $icms[$item->codigo_produto] = ($icms[$item->codigo_produto]!='NULL') ? $icms[$item->codigo_produto] : 0 ;

            //Pega primeira imagem
            $directory="../public/produto_imagem/".$item->codigo_produto."/";
            $files = scandir($directory);
            if (strlen($files[2])>2){
                $img = '/produto_imagem/'.$item->codigo_produto.'/'.$files[2];

                $image_size = GetImageSize($directory.$files[2]);
                if($image_size[0]>$image_size[1]){$big_side=$image_size[0];$small_side=$image_size[1];}
                else{$big_side=$image_size[1];$small_side=$image_size[0];}

                $i = round($small_side*95/$big_side, 2);
                $i = round((95-$i)/2);

                if($image_size[0]<$image_size[1])
                {
                    $pd='style="padding:0 '.$i.'px;"';
                } else
                {
                    $pd='style="padding:'.abs($i).'px 0;"';
                }
            } else
            {
                $img = "/img/no-image-placeholder.png";
                $pd='';
            }

            $thumb[$item->codigo_produto]=$img;
            $padding[$item->codigo_produto]=$pd;
        endforeach;

        if($fabr == '' || is_null($fabr))
            $this->session->fabricantesFilter = $fabricantes;

        
        //Insert no Search Log
        $user_id = ($this->session->auth['id']) ? $this->session->auth['id'] : 0;

        //Insere na search query

        $this->modelsManager->createQuery("
            INSERT INTO LogSearchQuery (user_id, content) 
            VALUES ($user_id,'" . ltrim($searchquery, " ") . "')")
        ->execute();
     
        //Variáveis para as views
        $this->view->searchquery = $searchquery;
        $this->view->fabr = $fabr;
        $this->view->price = $price;
        $this->view->origem = $origem;
        $this->view->icms = $icms;
        $this->view->fabricantes = $this->session->fabricantesFilter;
        $this->view->page = $page;
        $this->view->thumb = $thumb;
        $this->view->padding = $padding;
        $this->view->documents_products = ProdutoCoreService::getDocuments($products);
      
    }


    public function logsearchqueryAction($action)
    {
        if (!in_array($action, ['busca', 'termos', 'usuarios'])
            || $this->session->auth['role'] !== 'administrador'
        ) {
            $this->response->redirect('/');
        }

        $service = new LogSearchQueryService();
        $methodName = 'getPage' . ucfirst($action);
        $this->view->page = $service->$methodName();
        $this->view->action = $action;
    }

    public function createAction()
    {
        try {
            if (!$this->request->isPost()) {
                return $this->dispatcher->forward(
                    [
                        "controller" => "produto",
                        "action" => "index",
                    ]
                );
            }

            $form = new ProdutoForm;
            $produto = new ProdutoCore();
            $produto->data_de_cadastro = date('Y-m-d H:i:s');
            $data = $this->request->getPost();

            if (!$form->isValid($data, $produto)) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }

                return $this->dispatcher->forward(
                    [
                        "controller" => "produto",
                        "action" => "new",
                    ]
                );
            }

            $produto->tipo = intval($this->request->getPost('produto_tipo'));
            if ($produto->save() == false) {
                foreach ($produto->getMessages() as $message) {
                    $this->flash->error($message);
                }

                return $this->dispatcher->forward(
                    [
                        "controller" => "produto",
                        "action" => "new",
                    ]
                );
            }

            $form->clear();

            $this->persistent->resetSearchParams = true;

            $this->flash->success("Produto criado com sucesso.");

            return $this->dispatcher->forward(
                [
                    "controller" => "produto",
                    "action" => "admin",
                ]
            );
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
        }
    }

    public function editAction($codigo_produto)
    {
        /* ********************* */
        /* Pega imagens e anexos */
        /* ********************* */
        /* ********************* */
        $image_directory = "../public/produto_imagem/" . $codigo_produto . "/";
        if (is_dir($image_directory)) {
            $image_files = scandir($image_directory);
            $image_files = array_splice($image_files, 2);
        } else {
            $image_files = false;
        }

        $document_directory = "../public/produto_documento/" . $codigo_produto . "/";
        if (is_dir($document_directory)) {
            $document_files = scandir($document_directory);
            $document_files = array_splice($document_files, 2);
        } else {
            $document_files = false;
        }

        $tags = ProdutoTags::findFirstByCodigoProduto($codigo_produto);
        $tags = ($tags) ? $tags->tags : '';

        $this->view->doc = $document_files;
        $this->view->img = $image_files;

        /* CATEGORIAS */
        // Pegar as categorias vinculadas ao produto
        $categoriaItemService = new ProdutoCategoriaItemService();
        $categories = $categoriaItemService->getAdminListEdit($codigo_produto);
        $this->view->categories = $categories;
        $this->view->hasCategories = ProdutoCategoriaItem::count("codigo_produto = $codigo_produto");

        if (!$this->request->isPost()) {
            $produto_descricao = ProdutoDescricao::findFirstByCodigoProduto($codigo_produto);
            $produto = ProdutoCore::findFirstByCodigoProduto($codigo_produto);
            if (!$produto) {
                $this->flash->error("Produto não encontrado.");

                return $this->dispatcher->forward(
                    [
                        "controller" => "produto",
                        "action" => "index",
                    ]
                );
            }
            $this->view->tags = $tags;
            $this->view->produto = $produto;
            $this->view->produto_descricao = $produto_descricao;
            $this->persistent->delete_security = $this->persistent->unique;
            $this->view->delete_security = $this->persistent->delete_security;
            //$this->view->form = new ProdutoForm($produto, array('edit' => true));
            $this->persistent->searchParams = [];
        }
    }

    public function saveAction()
    {
        try {
            if (!$this->request->isPost()) {
                return $this->dispatcher->forward(
                    [
                        "controller" => "produto",
                        "action" => "admin",
                    ]
                );
            }

            $id = $this->request->getPost("id", "int");

            $produto = ProdutoCore::findFirstById($id);
            if (!$produto) {
                $this->flash->error("produto inexistente.");

                return $this->dispatcher->forward(
                    [
                        "controller" => "produto",
                        "action" => "admin",
                    ]
                );
            }

            //$form = new ProdutoForm;
            //$this->view->form = $form;

            $data = $this->request->getPost();

            if (!$form->isValid($data, $produto)) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }

                return $this->dispatcher->forward(
                    [
                                    "controller" => "produto",
                                    "action" => "edit",
                                    "params" => [$id]
                                ]
                );
            }
            $produto->_runValidator = false;
            if ($produto->save() == false) {
                foreach ($produto->getMessages() as $message) {
                    $this->flash->error($message);
                }

                return $this->dispatcher->forward(
                    [
                                    "controller" => "produto",
                                    "action" => "edit",
                                    "params" => [$id]
                                ]
                );
            }

            $form->clear();

            $this->flash->success("produto atualizado com sucesso.");
            $this->persistent->resetSearchParams = true;

            return $this->dispatcher->forward(
                [
                                "controller" => "produto",
                                "action" => "admin",
                            ]
            );
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
        }
    }

    public function resetParamsAction()
    {
        $this->persistent->resetSearchParams = true;
        return $this->dispatcher->forward(
            [
                            "controller" => "produto",
                            "action" => "admin",
                        ]
        );
    }

    public function adminAction()
    {
        $service = new ProdutoAdminService();

        $data = $service->getPageIndex();

        $this->view->userquery = $data['userquery'];
        $this->view->page = $data['page'];
    }

    public function limboAction()
    {
        if ($this->request->isPost()) {
            $data = $this->request->getPost();

            if (!isset($data['products'], $data['categories'])
                || !is_array($data['products'])
                || !is_array($data['categories'])) {
                $this->flash->error('Não foi possível salvar as informações');
                $this->response->redirect($this->request->getURI());
            }

            foreach ($data['products'] as $product) {
                foreach ($data['categories'] as $category) {
                    ProdutoCategoriaItem::add($product, $category, $this->session->auth['id']);
                }
            }
            $this->flash->success('Dados salvos com sucesso');
            $this->response->redirect($this->request->getURI());
        }

        $service = new ProdutoCategoriaEstruturaService();

        $categories = $service->getAdminList();

        $products = ProdutoCategoriaItem::getAdminLimbo();

        $this->view->categories = $categories;
        $this->view->products = $products;
    }

    public function categoriaAction()
    {
        if ($this->request->isPost()) {
            $service = new ProdutoCategoriaEstruturaService();

            $result = $service->addAdmin($this->request->getPost(), $this->session->auth['id']);
            if ($result['status'] === 'success') {
                $this->flash->success(implode('<br/>', $result['messages']));
                $this->response->redirect('/produto/categoria');
            } else {
                $this->flash->error(implode('<br/>', $result['messages']));
            }
            $form = $result['form'];
        } else {
            $form = new ProdutoCategoriaEstruturaForm(null, ['add' => true]);
        }

        $service = new ProdutoCategoriaEstruturaService();
        $categories = $service->getAdminList();

        $this->view->categories = $categories;
        $this->view->form = $form;
    }

    public function categoriaExcluirAction($id)
    {
        try {
            $service = new ProdutoCategoriaEstruturaService();
            $result = $service->remove($id, $this->session->auth['id']);
            if ($result === true) {
                $this->flash->success('Categoria removida com sucesso');
            } else {
                $this->flash->error('Não foi possível remover a categoria');
            }
        } catch (Exception $ex) {
            $this->flash->error($ex->getMessage());
        }
        $this->response->redirect('/produto/categoria');
    }

    public function updatetextAction($codigo_produto)
    {
        if ($this->request->getPost('descricao_texto') and $this->session->auth['role'] == 'administrador') :
            $descricao = $this->request->getPost('descricao_texto');

            ProdutoDescricao::saveAdmin([
                'codigo_produto' => $codigo_produto,
                'texto' => $descricao
            ],
                $this->session->auth['id'],
                'Update Text'
            );
        endif;

        return $this->response->redirect(
            'produto/edit/' . $codigo_produto
        );
    }

    public function updatesearchtagsAction($codigo_produto)
    {
        if ($this->request->getPost('search_tags') and $this->session->auth['role'] == 'administrador') :
            $tags = $this->request->getPost('search_tags');
        /*
          $query_update_tags = "INSERT INTO ProdutoTags (codigo_produto,tags) VALUES ('".$codigo_produto."','".$tags."')
          ON DUPLICATE KEY UPDATE tags='".$tags."'";
         */

        $find_tags = ProdutoTags::findFirstByCodigoProduto($codigo_produto);

        if ($find_tags) :
                $query_update_tags = "UPDATE ProdutoTags SET tags='" . $tags . "'
		WHERE codigo_produto=$codigo_produto"; else :
                $query_update_tags = "INSERT INTO ProdutoTags (codigo_produto,tags) VALUES ('" . $codigo_produto . "','" . $tags . "')";
        endif;

        $result_query_update_tags = $this->modelsManager->createQuery($query_update_tags);
        $execute_query_update_tags = $result_query_update_tags->execute();

        $author_id = $this->session->auth['id'];
        $content = "Update TAGS";

        $query_insert = "INSERT INTO LogProdutoContent (user_id, codigo_produto, content)
	VALUES ($author_id,'" . $codigo_produto . "','" . $content . "')";
        $result_query_insert = $this->modelsManager->createQuery($query_insert);
        $execute_query_insert = $result_query_insert->execute();

        return $this->response->redirect(
                'produto/edit/' . $codigo_produto
            );
        endif;
    }

    public function saveCategoriesAction($codigo_produto)
    {
        if ($this->request->isPost()) {
            $service = new ProdutoCategoriaItemService();

            $post = $this->request->getPost();

            $auth = $this->session->get('auth');

            $result = $service->addAdmin($post, $auth['id']);
            if ($result['status'] === 'success') {
                $this->flash->success(implode('<br>', $result['messages']));
            } else {
                $this->flash->error(implode('<br>', $result['messages']));
            }
        }
        $this->response->redirect('produto/edit/' . $codigo_produto);
    }

    public function unlinkAction($type, $codigo_produto, $file, $delete_security)
    {
        if ($type
                and $codigo_produto
                and $file
                and $delete_security == $this->persistent->delete_security
                and $this->session->auth['role'] == 'administrador') :
            switch ($type) {
                case 'img':
                    $content = "IMG - DELETE -- " . $file;
                    unlink('../public/produto_imagem/' . $codigo_produto . '/' . $file);
                    break;


                case 'doc':
                    $content = "DOC - DELETE -- " . $file;
                    unlink('../public/produto_documento/' . $codigo_produto . '/' . $file);
                    break;
            }

        $author_id = $this->session->auth['id'];


        $query_insert = "INSERT INTO LogProdutoContent (user_id, codigo_produto, content)
		VALUES ($author_id,'" . $codigo_produto . "','" . $content . "')";
        $result_query_insert = $this->modelsManager->createQuery($query_insert);
        $execute_query_insert = $result_query_insert->execute();

        return $this->response->redirect(
                'produto/edit/' . $codigo_produto
            );
        endif;


        echo $type . "<br><br>";
        echo $codigo_produto . "<br><br>";
        echo $file . "<br><br>";
        echo $delete_security . "<br><br>";
        echo $this->persistent->delete_security . "<br><br>";
    }

    public function uploadimgAction($codigo_produto)
    {
        #check if there is any file
        if ($this->request->hasFiles() == true) {
            $uploads = $this->request->getUploadedFiles();
            $isUploaded = false;

            // Create Directories
            ProdutoDescricao::createDirByCodigoProduto($codigo_produto);

            #do a loop to handle each file individually
            foreach ($uploads as $upload) {
                #define a “unique” name and a path to where our file must go
                $path = $_SERVER['DOCUMENT_ROOT'] . '/public/produto_imagem/' . $codigo_produto . '/' . strtolower($upload->getname());

                #move the file and simultaneously check if everything was ok
                ($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;

                // Log
                $author_id = $this->session->auth['id'];
                $content = "Upload IMG -- " . strtolower($upload->getname());
                ProdutoDescricao::saveLog($codigo_produto, $author_id, $content);
            }

            #if any file couldn’t be moved, then throw an message
            //($isUploaded) ? die('Files successfully uploaded.' . $path) : die('Some error ocurred.');

            return $this->response->redirect(
                'produto/edit/' . $codigo_produto
            );
        } else {
            #if no files were sent, throw a message warning user
            die('You must choose at least one file to send. Please try again.');
        }
    }

    public function uploaddocAction($codigo_produto)
    {
        #check if there is any file
        if ($this->request->hasFiles() == true) {
            $uploads = $this->request->getUploadedFiles();
            $isUploaded = false;

            #do a loop to handle each file individually
            foreach ($uploads as $upload) {
                #define a “unique” name and a path to where our file must go
                $file_name = UtilHelper::UploadFileName($upload->getname());

                $path = $_SERVER['DOCUMENT_ROOT'] . '/public/produto_documento/' . $codigo_produto . '/' . $file_name;

                #move the file and simultaneously check if everything was ok
                ($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;

                $author_id = $this->session->auth['id'];
                $content = "Upload DOC -- " . strtolower($upload->getname());

                $query_insert = "INSERT INTO LogProdutoContent (user_id, codigo_produto, content)
	            VALUES ($author_id,'" . $codigo_produto . "','" . $content . "')";
                $result_query_insert = $this->modelsManager->createQuery($query_insert);
                $execute_query_insert = $result_query_insert->execute();
            }

            #if any file couldn’t be moved, then throw an message
            //($isUploaded) ? die('Files successfully uploaded.' . $path) : die('Some error ocurred.');

            return $this->response->redirect(
                'produto/edit/' . $codigo_produto
            );
        } else {
            #if no files were sent, throw a message warning user
            die('You must choose at least one file to send. Please try again.');
        }
    }

    public function listlinkAction()
    {
        if ($this->session->auth['role'] == 'administrador') :
            $query_listlink = "SELECT codigo_produto FROM ProdutoCore WHERE status=1 ORDER BY codigo_produto ASC";
        $result_query_listlink = $this->modelsManager->createQuery($query_listlink);
        $execute_query_listlink = $result_query_listlink->execute();

        $this->view->listlink = $execute_query_listlink; else :
            return $this->response->redirect(
                '/'
            );
        endif;
    }

    public function produtoviewcountAction()
    {
        if ($this->session->auth['role'] == 'administrador') {
            $service = new LogProdutoViewService();
            $page = $service->getPageIndex();
            $this->view->page = $page;
        } else {
            return $this->response->redirect(
                '/'
            );
        }
    }

    public function geraestruturaAction()
    {
        $service = new ProdutoCategoriaEstruturaService();
        $service->generateTree();
    }

    public function loguserAction()
    {
        if ($this->session->auth['role'] != 'administrador' and $this->session->auth['role'] != 'vendedor'):
            $this->response->redirect('/');
        endif;

        if ($this->session->auth['role'] == 'administrador'):
            $service = new LogUserByRoleService();
            $page = $service->getPageAdminIndex();

            $vendedor=[];

            $vendedorQ = $this->modelsManager->createBuilder()
            ->columns(['Usuario.id',
            'Usuario.name'
            ])
            ->from('Usuario')
            ->where('Usuario.usuario_tipo = :tipo:',["tipo"=>2])
            ->getQuery()
            ->execute();

            foreach($vendedorQ as $v ):
                $vendedor[$v->id]=$v->name;
            endforeach;

            $this->view->vendedor = $vendedor;
            $this->view->page = $page;
        endif;

        if ($this->session->auth['role'] == 'vendedor'):
            $user_id_list = $this->modelsManager->createBuilder()
            ->columns(['Usuario.id'
            ])
            ->from('Usuario')
            ->where('Usuario.vendedor = :vendedor_id:',["vendedor_id"=>$this->session->auth['id']])
            ->getQuery()
            ->execute();

            $id_list = "(";
            foreach($user_id_list as $id):
                $id_list .="'".$id->id."',";
            endforeach;
            $id_list = substr($id_list,0,-1);
            $id_list .= ")";

            $service = new LogUserByRoleService();
            $page = $service->getPageVendedorIndex($id_list);
            $this->view->page = $page;
        endif;
    }

    public function produtostagsAction()
    {
    }
}