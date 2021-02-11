<?php

class ApiController extends ControllerBase{
    public function initialize(){
        $this->tag->setTitle('API');
        parent::initialize();
    }

    /**
     * Mocap para REST API formatação da home
     */
    public function indexAction(){
        $this->view->disable();

        $home_itens = [];

        $produtos_home = $this->modelsManager->createBuilder()
        ->columns(['ProdutoCore.descricao_site as descricao_site',
        'ProdutoCore.codigo_produto as codigo_produto',
        'ProdutoCore.ref as ref',
        'ProdutoCore.ncm as ncm',
        'ProdutoCore.unidade_venda as unidade_venda',
        'ProdutoFabricante.nome as fabricante_nome'
        ])
        ->from('ProdutoCore')
        ->join('ProdutoFabricante', 'ProdutoFabricante.sigla = ProdutoCore.sigla_fabricante')
        ->where('ProdutoCore.promo = :active:',["active"=>1])
        ->andwhere('ProdutoCore.sigla_fabricante = :fabricante1: 
            OR ProdutoCore.sigla_fabricante = :fabricante2:
            OR ProdutoCore.sigla_fabricante = :fabricante3:',
            ["fabricante1"=>"NX","fabricante2"=>"AP","fabricante3"=>"BD"])
        ->andwhere('ProdutoCore.status = :active:',["active"=>1])
        ->limit(21)
        ->getQuery()
        ->execute();

        /**
         * O método a seguir não funciona para modificar algumas variáveis
         * de uma query. Parece que o melhor caso é resolver na view ou criar
         * um objeto (ou array) que carregue as variáveis modificadas.
         * Nota: é mais seguro que isso ocorra na view.
         */
        for ($i=0; $i < count($produtos_home) ; $i++):
            $home_itens[$i]["descricao_site"] = $produtos_home[$i]->descricao_site;
            $home_itens[$i]["codigo_produto"] = $produtos_home[$i]->codigo_produto;
            $home_itens[$i]["fabricante_nome"] = $produtos_home[$i]->fabricante_nome;
            $home_itens[$i]["ref"] = $produtos_home[$i]->ref;
            $home_itens[$i]["ncm"] = $produtos_home[$i]->ncm;

            //Pega a primeira imagem -- Solulção horrível! Preciso ver isso com calma depois.
            $image_directory = "../public/produto_imagem/" . $produtos_home[$i]->codigo_produto . "/";
            if(is_dir($image_directory)):
                $image_files = scandir($image_directory);
                if(gettype($image_files) == "array" AND count($image_files)>2):
                    $image_size = GetImageSize($image_directory.$image_files[2]);
                    $image_files = 'produto_imagem/'.$produtos_home[$i]->codigo_produto.'/'.str_replace(' ', '%20', $image_files[2]);
                    //TODO - Fazer a matemática da coisa pra ficar certo
                    $w = $image_size[0];
                    $h = $image_size[1];
                    if($h>$w){
                        //Altura maior que largura deve possuir padding_left
                        $new_w = round((158*$w)/$h);
                        $home_itens[$i]["img_w"]=$new_w;
                        $home_itens[$i]["img_h"]=158;
                        $margin = round((158-$new_w)/2);
                        $home_itens[$i]["img_margin"] = 'margin-left:'.$margin.'px;';
                    }else{
                        //Largura maior que altura deve possuir margin_top
                        $new_h = round((158*$h)/$w);
                        $home_itens[$i]["img_w"]=158;
                        $home_itens[$i]["img_h"]=$new_h;
                        $margin = round((158-$new_h)/2);
                        $home_itens[$i]["img_margin"] = "margin-top:".$margin."px;";
                    }
                else:
                    $image_files = 'img/no-image-placeholder.png';
                    $home_itens[$i]["img_w"]=158;
                    $home_itens[$i]["img_h"]=158;
                    $home_itens[$i]["img_margin"] = "margin:0";
                endif;
            else:
                $image_files = 'img/no-image-placeholder.png';
                $home_itens[$i]["img_w"]=158;
                $home_itens[$i]["img_h"]=158;
                $home_itens[$i]["img_margin"] = "margin:0";
            endif;

            $home_itens[$i]["img_url"] = "https://gpcabling.com.br/".$image_files;

            switch ($produtos_home[$i]->unidade_venda):
                case 'MT':
                    $home_itens[$i]["unidade_venda"] = 'metro';
                    break;
                
                case 'PC':
                    $home_itens[$i]["unidade_venda"] = 'peça';
                    break;
            endswitch;
        endfor;

        //Create a response instance
        $response = new \Phalcon\Http\Response();

        /**
         * Isso é inseguro, apenas para testes
         */
        $response->setHeader('Total-Itens', count($home_itens));
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Headers', 'X-Requested-With');      
        $response->sendHeaders();

        //Content Type
        $response->setContentType('application/json', 'UTF-8');

        //Set the content of the response
        $response->setContent(json_encode($home_itens));

        //Return the response
        return $response;
    }

    public function getprodutoAction($codigo_produto=null){
        $this->view->disable();

        //Create a response instance
        $response = new \Phalcon\Http\Response();

        /**
         * Isso é inseguro, apenas para testes
         */
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Headers', 'X-Requested-With');      
        $response->sendHeaders();

        //Content Type
        $response->setContentType('application/json', 'UTF-8');

        if (!isset($codigo_produto)){
            $response->setContent(json_encode('NOT'));
            return $response;
            exit;
        }

        $produto = $this->modelsManager->createBuilder()
        ->columns(['ProdutoCore.descricao_site as descricao_site',
        'ProdutoCore.codigo_produto as codigo_produto',
        'ProdutoCore.ref as ref',
        'ProdutoCore.ncm as ncm',
        'ProdutoCore.unidade_venda as unidade_venda',
        'ProdutoFabricante.nome as fabricante_nome',
        'ProdutoDescricao.texto as texto'
        ])
        ->from('ProdutoCore')
        ->join('ProdutoFabricante', 'ProdutoFabricante.sigla = ProdutoCore.sigla_fabricante')
        ->join('ProdutoDescricao','ProdutoDescricao.codigo_produto = ProdutoCore.codigo_produto')
        ->where('ProdutoCore.codigo_produto = :codigo_produto:',
            ["codigo_produto"=>$codigo_produto])
        ->andwhere('ProdutoCore.sigla_fabricante = :fabricante1: 
            OR ProdutoCore.sigla_fabricante = :fabricante2:
            OR ProdutoCore.sigla_fabricante = :fabricante3:',
            ["fabricante1"=>"NX","fabricante2"=>"AP","fabricante3"=>"BD"])
        ->andwhere('ProdutoCore.status = :active:',["active"=>1])
        ->limit(1)
        ->getQuery()
        ->execute();

        if(count($produto)==0){
            $response->setContent(json_encode('NOT'));
            return $response;
            exit;
        }

        $item = [];

        for ($i=0; $i < count($produto) ; $i++):
            $item[$i]["descricao_site"] = $produto[$i]->descricao_site;
            $item[$i]["codigo_produto"] = $produto[$i]->codigo_produto;
            $item[$i]["fabricante_nome"] = $produto[$i]->fabricante_nome;
            $item[$i]["ref"] = $produto[$i]->ref;
            $item[$i]["ncm"] = $produto[$i]->ncm;
            $item[$i]["texto"] = ($produto[$i]->texto == null)? "Sem Texto<br />":$produto[$i]->texto;

            //Pega a primeira imagem -- Solulção horrível! Preciso ver isso com calma depois.
            $image_directory = "../public/produto_imagem/" . $produto[$i]->codigo_produto . "/";
            if(is_dir($image_directory)):
                $image_files = scandir($image_directory);
                if(gettype($image_files) == "array" AND count($image_files)>2):
                    $image_size = GetImageSize($image_directory.$image_files[2]);
                    $image_files = 'produto_imagem/'.$produto[$i]->codigo_produto.'/'.str_replace(' ', '%20', $image_files[2]);
                    //TODO - Fazer a matemática da coisa pra ficar certo
                    $w = $image_size[0];
                    $h = $image_size[1];
                    if($h>$w){
                        //Altura maior que largura deve possuir padding_left
                        $new_w = round((450*$w)/$h);
                        $item[$i]["img_w"]=$new_w;
                        $item[$i]["img_h"]=450;
                        $margin = round((450-$new_w)/2);
                        $item[$i]["img_margin"] = 'margin-left:'.$margin.'px;';
                    }else{
                        //Largura maior que altura deve possuir margin_top
                        $new_h = round((450*$h)/$w);
                        $item[$i]["img_w"]=450;
                        $item[$i]["img_h"]=$new_h;
                        $margin = round((450-$new_h)/2);
                        $item[$i]["img_margin"] = "margin-top:".$margin."px;";
                    }
                else:
                    $image_files = 'img/no-image-placeholder.png';
                    $item[$i]["img_w"]=450;
                    $item[$i]["img_h"]=450;
                    $item[$i]["img_margin"] = "margin:0px;";
                endif;
            else:
                $image_files = 'img/no-image-placeholder.png';
                $item[$i]["img_w"]=450;
                $item[$i]["img_h"]=450;
                $item[$i]["img_margin"] = "margin:0px;";
            endif;

            $item[$i]["img_url"] = "https://gpcabling.com.br/".$image_files;

            switch ($produto[$i]->unidade_venda):
                case 'MT':
                    $item[$i]["unidade_venda"] = 'metro';
                    break;
                
                case 'PC':
                    $item[$i]["unidade_venda"] = 'peça';
                    break;
            endswitch;
        endfor;


        //Set the content of the response
        $response->setContent(json_encode($item));

        //Return the response
        return $response;
    }
}