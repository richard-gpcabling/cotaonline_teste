<?php

use App\Services\ProdutoCoreService;

class ListasprontasController extends ControllerBase{
    public function initialize(){
        $this->tag->setTitle('API');
        parent::initialize();
    }

    /**
     * Index como admin
     */
    public function indexAction(){
        if($this->session->auth['role'] != 'administrador'):
            $this->flash->error("Você não possui permissão para acessar a página.");
            $this->view->error = TRUE;
    		return $this->dispatcher->forward(["controller" => "index","action" => "index"]);
        endif;

        $listas = ListaPronta::find();

        $prod_array = [];
        $criado_por_arr = [];
        $atualizado_por_arr = [];
        foreach ($listas as $item):
            $arr=(array) json_decode($item->produtos);

            /**
             * Criado POR
             */
            $user = $this->modelsManager->createBuilder()
            ->columns(['Usuario.name','Usuario.id'])
            ->from('Usuario')
            ->where('Usuario.id = :user_id:',["user_id"=>$item->criado_por])
            ->getQuery()
            ->execute();

            $criado_por_arr[$item->slug] = $user[0]["name"];

            /**
             * Atualizado POR
             */
            if($item->atualizado_por != null):
            $user = $this->modelsManager->createBuilder()
            ->columns(['Usuario.name','Usuario.id'])
            ->from('Usuario')
            ->where('Usuario.id = :user_id:',["user_id"=>$item->atualizado_por])
            ->getQuery()
            ->execute();

                $atualizado_por_arr[$item->slug] = $user[0]["name"];
            else:
                $atualizado_por_arr[$item->slug] = "Não atualizado";
            endif;

            foreach($arr as $a):
            $produto = $this->modelsManager->createBuilder()
            ->columns(['ProdutoCore.descricao_site as descricao_site',
            'ProdutoCore.codigo_produto as codigo_produto',
            'ProdutoFabricante.nome as fabricante_nome'
            ])
            ->from('ProdutoCore')
            ->join('ProdutoFabricante', 'ProdutoFabricante.sigla = ProdutoCore.sigla_fabricante')
            ->where('ProdutoCore.codigo_produto = :codigo_produto:',["codigo_produto"=>$a])
            ->getQuery()
            ->execute();

            $prod_array[$item->slug][$produto[0]["codigo_produto"]]=
                $produto[0]["codigo_produto"].
                " -- ".rtrim($produto[0]["fabricante_nome"]).
                " -- ".substr($produto[0]["descricao_site"],0,20)."..."
            ;
            endforeach;
        endforeach;



        $this->view->listas=$listas;
        $this->view->prod_array=$prod_array;
        $this->view->criado_por=$criado_por_arr;
        $this->view->atualizado_por=$atualizado_por_arr;
    }

    /**
     * Criar list
     */
    public function newlistAction(){
        if($this->session->auth['role'] != 'administrador'):
            $this->flash->error("Você não possui permissão para acessar a página.");
            $this->view->error = TRUE;
    		return $this->dispatcher->forward(["controller" => "index","action" => "index"]);
        endif;

        $produtos = $this->modelsManager->createBuilder()
        ->columns(['ProdutoCore.descricao_site as descricao_site',
        'ProdutoCore.codigo_produto as codigo_produto',
        'ProdutoFabricante.nome as fabricante_nome'
        ])
        ->from('ProdutoCore')
        ->join('ProdutoFabricante', 'ProdutoFabricante.sigla = ProdutoCore.sigla_fabricante')
        ->where('ProdutoCore.status = :active:',["active"=>1])
        //->limit(10)
        ->getQuery()
        ->execute();

        $this->view->produtos= $produtos;
    }

    public function createAction(){
        if($this->session->auth['role'] != 'administrador'):
            $this->flash->error("Você não possui permissão para acessar a página.");
            $this->view->error = TRUE;
    		return $this->dispatcher->forward(["controller" => "index","action" => "index"]);
        endif;

        if($this->session->auth['role'] != 'administrador'):
            $this->flash->error("Você não possui permissão para acessar a página.");
            $this->view->error = TRUE;
    		return $this->dispatcher->forward(["controller" => "index","action" => "index"]);
        endif;

        $titulo         = $this->request->getPost("titulo");
        $titulo         = (ctype_space($titulo))?null:$titulo;
        $slug           = $this->request->getPost("slug");
        $descricao      = $this->request->getPost("descricao");
        $descricao      = (ctype_space($descricao))?null:$descricao;
        $status         = ($this->request->getPost("status") == null)?0:1;
        $prod_array     = $this->request->getPost("produtos");

        $this->view->titulo     = $titulo;
        $this->view->slug       = $slug;
        $this->view->descricao  = $descricao;
        $this->view->status     = $status;
        $this->view->prod_array = $prod_array;

        if($prod_array == null OR $titulo == null OR $slug == null OR $descricao == null):
            $message = '';
            $message .= ($titulo == null)?
                    $this->flash->error("Por favor, selecione um título para a lista."):'';
            $message .= ($slug == null AND $titulo != null)?
                    $this->flash->error("Por favor, modifique o título até que a slug torne-se válida."):'';
            $message .= ($descricao == null)?
                    $this->flash->error("Por favor, insira uma breve descrição da lista para os clientes."):'';
            $message .= ($prod_array == null)?
                    $this->flash->error("Por favor, selecione produtos para incluir na lista."):'';
            //$this->flash->error($message);
            $this->view->error = TRUE;
    		return $this->dispatcher->forward(["controller" => "listasprontas","action" => "newlist"]);
        endif;

        try {
            $lista_pronta = new ListaPronta();
            $lista_pronta->titulo       = $titulo;
            $lista_pronta->slug         = $slug;
            $lista_pronta->descricao    = $descricao;
            $lista_pronta->produtos     = json_encode($prod_array);
            $lista_pronta->status       = $status;
            $lista_pronta->criado_por   = $this->session->auth['id'];
            $lista_pronta->save();

            $this->flash->success("Nova lista criada com sucesso.");
            return $this->response->redirect('/listasprontas');
        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
            return $this->dispatcher->forward(["controller" => "listasprontas","action" => "newlist"]);
        }
    }

    /**
     * Edit Action
     */
    public function editAction($slug){
        if($this->session->auth['role'] != 'administrador'):
            $this->flash->error("Você não possui permissão para acessar a página.");
            $this->view->error = TRUE;
    		return $this->dispatcher->forward(["controller" => "index","action" => "index"]);
        endif;

        $lista = ListaPronta::findFirstBySlug($slug);

        $titulo         = $lista->titulo;
        $slug           = $lista->slug;
        $descricao      = $lista->descricao;
        $status         = $lista->status;
        $prod_array     = json_decode($lista->produtos);

        $this->view->lista     = $lista;

        $this->view->titulo     = $titulo;
        $this->view->slug       = $slug;
        $this->view->descricao  = $descricao;
        $this->view->status     = $status;
        $this->view->prod_array = $prod_array;

        $produtos = $this->modelsManager->createBuilder()
        ->columns(['ProdutoCore.descricao_site as descricao_site',
        'ProdutoCore.codigo_produto as codigo_produto',
        'ProdutoFabricante.nome as fabricante_nome'
        ])
        ->from('ProdutoCore')
        ->join('ProdutoFabricante', 'ProdutoFabricante.sigla = ProdutoCore.sigla_fabricante')
        ->where('ProdutoCore.status = :active:',["active"=>1])
        //->limit(10)
        ->getQuery()
        ->execute();

        $this->view->produtos= $produtos;

    }

    public function updateAction($slug){
        if($this->session->auth['role'] != 'administrador'):
            $this->flash->error("Você não possui permissão para acessar a página.");
            $this->view->error = TRUE;
    		return $this->dispatcher->forward(["controller" => "index","action" => "index"]);
        endif;

        $lista = ListaPronta::findFirstBySlug($slug);

        $titulo         = $this->request->getPost("titulo");
        $titulo         = (ctype_space($titulo))?null:$titulo;
        $slug           = $this->request->getPost("slug");
        $descricao      = $this->request->getPost("descricao");
        $descricao      = (ctype_space($descricao))?null:$descricao;
        $status         = ($this->request->getPost("status") == null)?0:1;
        $prod_array     = $this->request->getPost("produtos");

        $this->view->titulo     = $titulo;
        $this->view->slug       = $slug;
        $this->view->descricao  = $descricao;
        $this->view->status     = $status;
        $this->view->prod_array = $prod_array;

        if($prod_array == null OR $titulo == null OR $slug == null OR $descricao == null):
            $message = '';
            $message .= ($titulo == null)?
                    $this->flash->error("Por favor, selecione um título para a lista."):'';
            $message .= ($slug == null AND $titulo != null)?
                    $this->flash->error("Por favor, modifique o título até que a slug torne-se válida."):'';
            $message .= ($descricao == null)?
                    $this->flash->error("Por favor, insira uma breve descrição da lista para os clientes."):'';
            $message .= ($prod_array == null)?
                    $this->flash->error("Por favor, selecione produtos para incluir na lista."):'';
            //$this->flash->error($message);
            $this->view->error = TRUE;
    		return $this->dispatcher->forward(["controller" => "listasprontas","action" =>"edit"]);
        endif;

        try {
            $lista->titulo          = $titulo;
            $lista->slug            = $slug;
            $lista->descricao       = $descricao;
            $lista->produtos        = json_encode($prod_array);
            $lista->status          = $status;
            $lista->atualizado_por  = $this->session->auth['id'];
            $lista->updated_at      = date("Y-m-d H:i:s");;
            $lista->save();

            $this->flash->success("Lista atualizada com sucesso.");
            return $this->response->redirect('/listasprontas');
        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
            return $this->dispatcher->forward(["controller" => "listasprontas","action" => "edit"]);
        }
    }

    /**
     * Lista de listas para user
     */
    public function listsAction(){
        $listas = ListaPronta::findByStatus(1);

        $this->view->listas=$listas;
    }

    /**
     * Lista individual
     */
    public function listAction($slug){
        $list = ListaPronta::findFirstBySlug($slug);

        $produtos = str_replace("]","",str_replace("[","", str_replace('"',"'",$list->produtos)));

        $query_produto="SELECT
        ProdutoCore.codigo_produto,
        TRIM(ProdutoFabricante.nome) AS fabricante_nome,
        ProdutoCore.descricao_site,
        ProdutoCore.descricao_sys,
        ProdutoCore.ref,
        ProdutoCore.unidade_venda,
        ProdutoCore.moeda_venda,
        ProdutoCore.tipo_fiscal,
        ProdutoCore.promo,
        ProdutoEstoque.total_estoque AS total_estoque
        FROM ProdutoCore
        JOIN ProdutoEstoque ON ProdutoEstoque.codigo_produto = ProdutoCore.codigo_produto
        JOIN ProdutoFabricante ON ProdutoFabricante.sigla = ProdutoCore.sigla_fabricante
        WHERE ProdutoCore.codigo_produto in (".$produtos.") AND ProdutoCore.status = 1 ORDER BY total_estoque DESC,tipo_fiscal DESC";
        $result_produto = $this->modelsManager->createQuery($query_produto);
        $produto = $result_produto ->execute();

        $this->view->setVar('produto',$produto);

        //Pegar descrições dos produtos
        /**
         * TODO - Confusão de IFs... Tentar fazer de modo mais inteligente como função em Services ou para View.
         */
        foreach ($produto as $item) {
            //Pega primeira imagem
            $directory="../public/produto_imagem/".$item->codigo_produto."/";
            if(is_dir($directory)){
            $files = scandir($directory);
            if (strlen($files[2])>2){
                //TODO -- Não pegar o .db
                $img = '/produto_imagem/'.$item->codigo_produto.'/'.$files[2];

                $image_size = GetImageSize($directory.$files[2]);
                if($image_size[0]>$image_size[1]) {
                    $big_side=$image_size[0];$small_side=$image_size[1];
                } else {
                    $big_side=$image_size[1];$small_side=$image_size[0];
                }

                $i = round($small_side*95/$big_side, 2);
                $i = round((95-$i)/2);

            if($image_size[0]<$image_size[1]){
                $pd='style="padding:0 '.$i.'px;"';
            } else {
                $pd='style="padding:'.abs($i).'px 0;"';
            }

            }

            } else {
                $img = "/img/no-image-placeholder.png";
                $pd='';
            }


            $thumb[$item->codigo_produto]=$img;
            $padding[$item->codigo_produto]=$pd;
        }
        $this->view->thumb = $thumb;
        $this->view->padding = $padding;

        //Começa o find de produto por produto. E definição da variável price.
        if (isset($this->session->auth)
            and isset($this->session->auth['id'])
            and $this->session->auth['custo_pagina'] != 0):
            $price=[];
            $origem=[];
            $taxas=[];
            $forma_preco = new ProdutoCoreService;

            foreach ($produto as $item){
                $preco= $forma_preco->formaPreco($this->session->auth['id'],$item->codigo_produto);
                $price[$item->codigo_produto]= $preco->price;
                $origem[$item->codigo_produto]= $preco->origem;
                $icms[$item->codigo_produto] = ($preco->taxas['c_icms']!='NULL') ? $preco->taxas['c_icms'] : 0 ;
            }
            $this->view->setVar('price', $price);
            $this->view->setVar('origem', $origem);
            $this->view->setVar('icms', $icms);

        endif;

        $this->view->produto_documents = ProdutoCoreService::getDocuments($produto);

        $this->view->titulo = $list->titulo;
    }
}
