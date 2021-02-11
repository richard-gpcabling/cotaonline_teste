<?php

//TODO - Refatorar orcamento -- páginas dom event

use App\Forms\OrcamentoForm;
use App\Services\OrcamentoService;
use App\Services\ProdutoCoreService;

class OrcamentoController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Orçamentos');
        parent::initialize();
    }

    public function fixAction(){
        /*
        if($this->session->auth['role']=='administrador'):
        $o = $this->modelsManager->createBuilder()
        ->columns(["Orcamento.id as o_id",
                    "Orcamento.data_de_criacao as o_data",
                    "Orcamento.usuario_id as o_uid",
                    "Orcamento.ucode as o_ucode",
                    "Orcamento.tabela as o_tab",
                    "OrcamentoItem.id as oi_id",
                    "OrcamentoItem.rawcost as oi_rc",
                    "OrcamentoItem.fator as oi_f",
                    "OrcamentoItem.codigo_produto as oi_cp"
        ])
        ->from('Orcamento')
        ->join('OrcamentoItem', 'Orcamento.id=OrcamentoItem.orcamento_id ')
        ->where('Orcamento.id > :id: ', ["id"=>"1872"])
        ->getQuery()
        ->execute();


            foreach ($o as $item){
                $user_vars = ViewUsuarioClienteCustoVars::findFirstById($item->o_uid);

                $forma_preco = ProdutoCoreService::formaPreco($item->o_uid,$item->oi_cp);
                $taxas = json_encode($forma_preco->taxas);
                $taxas = strval($taxas);
                $cost_vars = [
                    "origem_estado"=>$forma_preco->origem,
                    "origem_empresa"=>$forma_preco->origem_empresa,
                    "tipo_fiscal"=>$user_vars->custo_sys_string,
                    "mark_up_fabricantes"=>json_decode($user_vars->mark_up_fabricantes, true)
                ];
                $cost_vars = json_encode($cost_vars);
                $cost_vars = strval($cost_vars);

                $fix_orcamento_item = OrcamentoItem::findFirstById($item->oi_id);
                $fix_orcamento_item->rawcost = $taxas;
                $fix_orcamento_item->fator = $cost_vars;
                $fix_orcamento_item->markup = $user_vars->tabela_de_custo.".".$forma_preco->mark_up;
                $fix_orcamento_item->updated_at = $item->o_data;
                $fix_orcamento_item->save();


                echo $item->oi_cp."--".$taxas."----".$cost_vars."<br>";
            }
        
        endif;
        */
    }

    public function indexAction()
    {
        $service = new OrcamentoService();
        $this->view->count_orc = $service->getPageIndexDashboard();

        $this->searchAction();
    }

    public function searchAction()
    {
        try {
            $service = new OrcamentoService();

            $data = $service->getPageIndex($this->persistent);

            $this->view->client = $data['client']; // set variables for interface for persistent filters on different pages
            $this->view->query = $data['userquery'];
            $this->view->initdate = $data['initdate'];
            $this->view->enddate = $data['enddate'];
            $this->view->status = $data['status'];
            $this->view->period = $data['period'];
            $this->persistent->resetSearchParams = $data['resetSearchParams'];
            $this->view->page = $data['page'];
            $this->persistent->searchParams = $data['searchParams'];
        } catch (Exception $ex) {

        }
    }

    public function newAction()
    {
        $this->view->form = new OrcamentoForm(null, array('edit' => true));
    }

    public function createAction()
    {
        // try{
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "orcamento",
                    "action"     => "index",
                ]
            );
        }

        $form = new OrcamentoForm;
        $Orcamento = new Orcamento();

        $data = $this->request->getPost();
        if (!$form->isValid($data, $Orcamento)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "orcamento",
                    "action"     => "new",
                ]
            );
        }
        $Orcamento->tipo = intval($this->request->getPost('Orcamento_tipo'));
        if ($Orcamento->save() == false) {
            foreach ($Orcamento->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "orcamento",
                    "action"     => "new",
                ]
            );
        }

        $form->clear();

        $this->persistent->resetSearchParams = true;

        $this->flash->success("Orcamento criado com sucesso.");

        return $this->dispatcher->forward(
            [
                "controller" => "orcamento",
                "action"     => "index",
            ]
        );

        // } catch  (\Exception $e) {
        //   echo get_class($e), ": ", $e->getMessage(), "\n";
        //   echo " File=", $e->getFile(), "\n";
        //   echo " Line=", $e->getLine(), "\n";
        //   echo $e->getTraceAsString();
        // }
    }
    public function editAction($id) {
        //try {
            $mysqlQuery ="";
            $bind=[];
            $bind['id']= $id;
            $tabeladecusto ="";
            $tabeladecustoraw =false;
            $cliente_tipo =false;
            $cliente_unidade_policom ="";
            $clientrelated = false;
            $markup = 'null';
            $result = $this->modelsManager->createBuilder()
                        ->columns(["Orcamento.usuario_id as orcamento_usuario_id",
                            "UnidadePolicom.estado as cliente_unidade_policom",
                            "ClienteCore.cnpj_cpf as cliente_cnpj",
                            "ClienteCore.destino as cliente_estado",
                            "UnidadePolicom.estado as unidade_estado",
                            "ClienteTipoFiscal.tipo_fiscal as cliente_tipo",
                            "ClienteCore.*",
                            "ClienteCore.mark_up_geral as markup_geral",
                            "Usuario.vendedor as vendedor"])
                        ->from('Orcamento')
                        ->join('Usuario', 'Usuario.id=Orcamento.usuario_id')
                        ->join('ClienteCore', 'ClienteCore.clienteUcode = Usuario.codigo_cliente')
                        ->join('ClienteTipoFiscal', 'ClienteTipoFiscal.id = ClienteCore.tipo_fiscal')
                        ->join('UnidadePolicom', 'ClienteCore.origem_de_compra = UnidadePolicom.estado')
                        ->where(' Orcamento.id= :id: ', $bind)
                        ->getQuery()
                        ->execute();
            $test =  $result->toArray();
            $emptyArray = [];
            if ($test != $emptyArray &&
                ($result[0]->vendedor == $this->session->auth['id'] || $result[0]->orcamento_usuario_id == $this->session->auth['id'])) {
                $clientrelated = true;
            }
            if ($test != $emptyArray) {
                $cliente_endereco = ucfirst(strtolower($result[0]->cliente_endereco));
                $cliente_bairro = ucfirst(strtolower($result[0]->cliente_bairro));
                $cliente_cidade = ucfirst(strtolower($result[0]->cliente_cidade));
                $cliente_cep = ucfirst(strtolower($result[0]->cliente_cep));
                $cliente_cnpj = ucfirst(strtolower($result[0]->cliente_cnpj));
                $inscricao_estadual = ucfirst(strtolower($result[0]->cliente_inscricao_estadual));
            }
            if ($test != $emptyArray && $result[0]->cliente_estado != null && $result[0]->unidade_estado != null) {
                $cliente_estado = ucfirst(strtolower($result[0]->cliente_estado));
                $unidade_estado = ucfirst(strtolower($result[0]->unidade_estado));
                $cliente_tipo   = $result[0]->cliente_tipo;
                $cliente_unidade_policom   = $result[0]->cliente_unidade_policom;
                $tabeladecustoRaw  = strtolower($result[0]->tabela_custo);
                // $this->flash->notice($tabeladecustoRaw);
                $pattern = '/(custo)_(..)_(..)/';
                $origin  = preg_replace($pattern, '$2', $tabeladecustoRaw);
                $destination  = preg_replace($pattern, '$3', $tabeladecustoRaw);
                $tabeladecusto = 'Custo'.ucfirst(strtolower($origin)).ucfirst(strtolower($destination));
            }
            if (count($result) >0 && $result[0]->markup!=null && $result[0]->markup!="") {
                $markup   = "produto_mark_up.p".(sprintf("%02d", $result[0]->markup));
            } else {
                $markup = 'null';
            }

            $tablecheck = true;
            if (!$tablecheck || $tabeladecusto == '' or $tabeladecusto == null) {
                // needs to set a cost table on invoice to access products prices
                $Orcamento=$this->modelsManager->createBuilder()
                ->columns(["Orcamento.*",
                    "ProdutoCore.id",
                    "ProdutoCore.unidade_venda",
                    "ProdutoCore.status as status",
                    "ProdutoCore.descricao_site as descricao_site",
                    "ProdutoCore.descricao_sys as descricao_sys",
                    "ProdutoCore.sigla_fabricante as sigla_fabricante",
                    "ProdutoCore.codigo_produto as codigo_produto",
                    "ProdutoCore.ref as ref",
                    "ProdutoCore.moeda_venda as moeda_venda",
                    "ProdutoFabricante.nome as fabricante_nome",
                    "Orcamento.total",
                    "OrcamentoItem.quantidade",
                    "OrcamentoItem.unitario",
                    "OrcamentoItem.subtotal",
                    "OrcamentoItem.rawcost",
                    "OrcamentoItem.fator",
                    "OrcamentoItem.updated_at",
                    "Usuario.id as usuario_id",
                    "Usuario.name as usuario_nome",
                    "ClienteCore.clienteUcode as cliente_id",
                    "ClienteCore.nome as cliente_nome",
                    "ClienteCore.razao_social as razao_social",
                    "ClienteCore.destino as destino",
                    "ClienteCore.cnpj_cpf as cnpj_cpf",
                    "ProdutoCore.ncm as ncm"])
                ->from('Orcamento')
                ->join('OrcamentoItem', 'Orcamento.id=OrcamentoItem.orcamento_id ')
                ->join('ProdutoCore', 'ProdutoCore.codigo_produto=OrcamentoItem.codigo_produto ')
                ->join('ProdutoEstoque', 'ProdutoEstoque.codigo_produto = ProdutoCore.codigo_produto ')
                ->join('ProdutoFabricante', 'ProdutoFabricante.sigla = ProdutoCore.sigla_fabricante ')
                ->join('Usuario', 'Usuario.id=Orcamento.usuario_id')
                ->join('ClienteCore', 'Usuario.codigo_cliente = ClienteCore.clienteUcode')
                ->where('Orcamento.id= :id: ', ["id"=>$id])
                ->getQuery()
                ->execute();
                if (!$Orcamento) {
                    $this->flash->error("Orcamento não encontrado.");

                    return $this->dispatcher->forward(
                        [
                            "controller" => "orcamento",
                            "action"     => "index",
                        ]
                    );
                }
                // $this->flash->notice(json_encode($Orcamento));
            } else {
                // $this->flash->notice("maybe");
                $Orcamento=$this->modelsManager->createBuilder()
                /*->columns(["Orcamento.*","ProdutoCore.id ","ProdutoCore.status as status","ProdutoCore.descricao_sys as descricao_sys",
                    "ProdutoCore.sigla_fabricante as sigla_fabricante","ProdutoCore.codigo_produto as codigo_produto",
                    "ProdutoCore.possui_st as possui_st","ProdutoCore.ref as ref","ProdutoCore.observacoes_internas as observacoes_internas",
                    $tabeladecusto.'.'.$cliente_tipo.' as custo_produto',"OrcamentoItem.quantidade","Usuario.id as usuario_id",
                    "Usuario.name as usuario_nome","Cliente.uCode as cliente_id","Cliente.nome as cliente_nome"])*/
                ->columns(["Orcamento.*","Orcamento.id as orcamento_id","ProdutoCore.id ","ProdutoCore.status as status",
                    "ProdutoCore.descricao_sys as descricao_sys","ProdutoCore.sigla_fabricante as sigla_fabricante",
                    "ProdutoCore.codigo_produto as codigo_produto","ProdutoCore.possui_st as possui_st",
                    "ProdutoCore.ref as ref","ProdutoCore.observacoes_internas as observacoes_internas",
                    $tabeladecusto.'.'.$cliente_tipo.' as custo_produto',"Orcamento.total","OrcamentoItem.quantidade",
                    "OrcamentoItem.subtotal","OrcamentoItem.unitario","Usuario.id as usuario_id","Usuario.name as usuario_nome",
                    "Cliente.codigo_policom as cliente_codigo_policom","Cliente.uCode as cliente_id","Cliente.nome as cliente_nome",
                    "ProdutoCore.ncm as ncm","Fabricante.nome as fabricante_nome"])
                ->from('Orcamento')
                ->join('OrcamentoItem', 'Orcamento.id=OrcamentoItem.orcamento_id ')
                ->leftjoin('ProdutoCore', 'ProdutoCore.codigo_produto=OrcamentoItem.codigo_produto')
                ->leftjoin('ProdutoFabricante', 'ProdutoFabricante.sigla = ProdutoCore.sigla_fabricante')
                ->leftjoin($tabeladecusto, $tabeladecusto.'.produto_id=ProdutoCore.codigo_produto ')
                ->leftjoin('ProdutoEstoque', 'ProdutoEstoque.codigo_produto=ProdutoCore.codigo_produto ')
                ->leftjoin('ProdutoPrecoEspecial', 'ProdutoPrecoEspecial.produto_id=ProdutoCore.id')
                ->join('Usuario', 'Usuario.id=Orcamento.usuario_id')
                ->leftjoin('Cliente', 'Usuario.codigo_cliente=Cliente.uCode')
                ->where(' Orcamento.id= :id: ', $bind)
                ->getQuery()
                ->execute();

                if (!$Orcamento) {
                    $this->flash->error("Orcamento não encontrado.");

                    return $this->dispatcher->forward(
                        [
                            "controller" => "orcamento",
                            "action"     => "index",
                        ]
                    );
                }
            }
            $OrcamentoForm = Orcamento::findFirstById($id);
            // $this->flash->notice(json_encode($this->session->auth));
            $myuser = Usuario::findFirstById($Orcamento[0]->usuario_id);
            $vendedor = null;
            if ($myuser) {
                $vendedor = Usuario::findFirstById($myuser->vendedor);
            }

            $this->view->vendedor = $vendedor;
            //$this->view->cliente_codigo_policom = $cliente_codigo_policom;
            $this->view->id = $id;
            $this->view->clientrelated = $clientrelated;
            $this->view->clientetipo  = $cliente_tipo;
            $this->view->tabeladecusto = $tabeladecusto;

            /* Fabricante Nome Workaround pois não funciona no método 'normal' se Usuário não estiver associado */
            $fabricante_wa = ProdutoFabricante::find();
            $fab_list=[];

            foreach ($fabricante_wa as $key => $value) :
                $fab_list[$value->sigla]=$value->nome;
            endforeach;

            $this->view->fab_list= $fab_list;
            $this->view->invoice = $Orcamento;
            $this->view->markup = $markup;
            if ($this->session->auth['orcamento_edicaorelacionado'] || $this->session->auth['orcamento_edicaonaorelacionado']) {
                $this->view->form = new OrcamentoForm($OrcamentoForm, array('edit' => true));
            } else {
                $this->view->form = new OrcamentoForm($OrcamentoForm, array('edit' => false)); // changes invoice mode to read-only
            }


            $impostos=[];
            $origem_estado=[];
            $empresa=new stdClass();
    
            foreach($Orcamento as $item):
                $rawcost  = json_decode($item->rawcost,true);
                $fator  = json_decode($item->fator,true);
                $impostos[$item->codigo_produto]        = new stdClass();
                $origem_estado[$item->codigo_produto]   = new stdClass();
                if(5>6):
                    $origem_estado[$item->codigo_produto]   = $fator['origem_estado'];
                endif;
                $origem_estado[$item->codigo_produto]   = (isset($fator['origem_estado']))? $fator['origem_estado']:'N/A';
                $impostos[$item->codigo_produto]->cst   = $rawcost['cst'];
                $impostos[$item->codigo_produto]->icms  = ($rawcost['c_icms'] == 'NULL')? 0 : str_replace('.',',',$rawcost['c_icms']);
                $impostos[$item->codigo_produto]->ipi   = ($rawcost['c_ipi'] == 'NULL')? 0 : str_replace('.',',',$rawcost['c_ipi']);
                $impostos[$item->codigo_produto]->st    = ($rawcost['c_st'] == 'NULL')? "Não possui" : str_replace('.',',',$rawcost['c_st']);
            endforeach;
    
            foreach($Orcamento as $item):
                $empresa->tipo      = json_decode($item->fator, true);
                $empresa->tipo      = str_replace('_',' ',$empresa->tipo['tipo_fiscal']);
                $empresa->tipo      = ucwords(rtrim(ltrim(str_replace('custo','',$empresa->tipo))));
                $empresa->razao     = $item->razao_social;
                $empresa->cnpj      = $item->cnpj_cpf;
                $empresa->destino   = $item->destino;
                $empresa->data      = date("d/m/Y", strtotime($item->updated_at));
                $empresa->validade  = date("d/m/Y", strtotime($item->updated_at.' +5 days'));
            endforeach;

            $impostos_fixos = new stdClass;
            $impostos_fixos->pis = ProdutoImpostoFixo::findFirstByNome('pis');
            $impostos_fixos->pis = str_replace('.',',',$impostos_fixos->pis->total);
            $impostos_fixos->cofins = ProdutoImpostoFixo::findFirstByNome('cofins');
            $impostos_fixos->cofins = str_replace('.',',',$impostos_fixos->cofins->total);
            $impostos_fixos->ircsll = ProdutoImpostoFixo::findFirstByNome('ir/csll');
            $impostos_fixos->ircsll = str_replace('.',',',$impostos_fixos->ircsll->total);

            $perm = FALSE;

            if($myuser->id == $this->session->auth['id'] 
                OR $vendedor->id == $this->session->auth['id']
                OR $this->session->auth['role'] == 'administrador'):
                $perm = TRUE;
            endif;

            $this->view->id = $id;
            $this->view->perm = $perm;
            $this->view->impostos_fixos = $impostos_fixos;
            $this->view->impostos = $impostos;
            $this->view->origem = $origem_estado;
            $this->view->empresa = $empresa;

            $this->persistent->searchParams = [];
        /*
        } catch (Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
        }
        */
    }
    public function saveAction()
    {
        try {
            if (!$this->request->isPost()) {
                return $this->dispatcher->forward(
                    [
                        "controller" => "orcamento",
                        "action"     => "index",
                    ]
                );
            }

            $id = $this->request->getPost("id", "int");

            $Orcamento = Orcamento::findFirstById($id);
            if (!$Orcamento) {
                $this->flash->error("Orcamento inexistente.");

                return $this->dispatcher->forward(
                    [
                        "controller" => "orcamento",
                        "action"     => "index",
                    ]
                );
            }

            $form = new OrcamentoForm;
            $this->view->form = $form;

            $data = $this->request->getPost();

            if (!$form->isValid($data, $Orcamento)) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }

                return $this->dispatcher->forward(
                    [
                        "controller" => "orcamento",
                        "action"     => "edit",
                        "params"     => [$id]
                    ]
                );
            }
            $Orcamento->_runValidator=false;
            if ($Orcamento->save() == false) {
                foreach ($Orcamento->getMessages() as $message) {
                    $this->flash->error($message);
                }

                return $this->dispatcher->forward(
                    [
                        "controller" => "orcamento",
                        "action"     => "edit",
                        "params"     => [$id]
                    ]
                );
            }

            $form->clear();

            $this->flash->success("Orçamento atualizado com sucesso.");
            $this->persistent->resetSearchParams = true;

            return $this->response->redirect("/orcamento/edit/".$id);

            return $this->dispatcher->forward(
                [
                    "controller" => "orcamento",
                    "action"     => "edit",
                    "params"     => [$id]
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
        $this->persistent->resetSearchParams =true;
        return $this->dispatcher->forward(
            [
                "controller" => "orcamento",
                "action"     => "index",
            ]
        );
    }
    public function createpdfAction($orcamento_id=null){
        $format = "pdf";

        $this->view->disable();

        $perm = 0;

        $orcamento = Orcamento::findFirstById($orcamento_id);

        $owner =  Usuario::findFirstById($orcamento->usuario_id);

        $v_gen = Usuario::findFirstById($owner->vendedor);
        $v_desc = UsuarioVendedorDescricao::findFirstByUserId($owner->vendedor);

        $vendedor = (object) array_merge((array)$v_gen,(array)$v_desc);
        
        if($this->session->auth['role'] == 'administrador'
        OR $this->session->auth['id'] == $owner->id
        OR $this->session->auth['id'] == $vendedor->user_id):
            $perm = 1;
        endif;

        $unidade_info = new stdClass();
        $unidade_info->pe = 0;
        $unidade_info->pp = 0;
        $unidade_info->po = 0;

        if($orcamento_id == null or $orcamento == null or $perm == 0){
            $this->flash->error("Orçamento inexistente");
            return $this->response->redirect("/");
        }

        $file_name = "proposta_comercial_gpcabling_".$orcamento_id;

        $orcamento = $this->modelsManager->createBuilder()
        ->columns(["Orcamento.*",
            "ProdutoCore.id",
            "ProdutoCore.unidade_venda",
            "ProdutoCore.status as status",
            "ProdutoCore.descricao_site as descricao_site",
            "ProdutoCore.descricao_sys as descricao_sys",
            "ProdutoCore.sigla_fabricante as sigla_fabricante",
            "ProdutoCore.codigo_produto as codigo_produto",
            "ProdutoCore.ref as ref",
            "ProdutoCore.moeda_venda as moeda_venda",
            "ProdutoFabricante.nome as fabricante_nome",
            "Orcamento.total",
            "OrcamentoItem.quantidade",
            "OrcamentoItem.unitario",
            "OrcamentoItem.subtotal",
            "OrcamentoItem.rawcost",
            "OrcamentoItem.fator",
            "OrcamentoItem.updated_at",
            "Usuario.id as usuario_id",
            "Usuario.name as usuario_nome",
            "Usuario.vendedor as usuario_vendedor",
            "ClienteCore.clienteUcode as cliente_id",
            "ClienteCore.nome as cliente_nome",
            "ClienteCore.razao_social as razao_social",
            "ClienteCore.destino as destino",
            "ClienteCore.cnpj_cpf as cnpj_cpf",
            "ProdutoCore.ncm as ncm"])
        ->from('Orcamento')
        ->join('OrcamentoItem', 'Orcamento.id=OrcamentoItem.orcamento_id ')
        ->join('ProdutoCore', 'ProdutoCore.codigo_produto=OrcamentoItem.codigo_produto ')
        ->join('ProdutoEstoque', 'ProdutoEstoque.codigo_produto = ProdutoCore.codigo_produto ')
        ->join('ProdutoFabricante', 'ProdutoFabricante.sigla = ProdutoCore.sigla_fabricante ')
        ->join('Usuario', 'Usuario.id=Orcamento.usuario_id')
        ->join('ClienteCore', 'Usuario.codigo_cliente = ClienteCore.clienteUcode')
        ->where('Orcamento.id= :id: ', ["id"=>$orcamento_id])
        ->getQuery()
        ->execute();

        $impostos=[];
        $origem_estado=[];
        $empresa=new stdClass();

        foreach($orcamento as $item):
            $rawcost  = json_decode($item->rawcost,true);
            $fator  = json_decode($item->fator,true);
            $impostos[$item->codigo_produto]        = new stdClass();
            $origem_estado[$item->codigo_produto]          = new stdClass();
            $origem_estado[$item->codigo_produto]          = $fator['origem_estado'];
            switch ($fator['origem_empresa']) {
                case 'pe':
                    $unidade_info->pe = 1;
                    break;
                
                case 'po':
                    $unidade_info->po = 1;
                    break;
                
                case 'pp':
                    $unidade_info->pp = 1;
                    break;
            }
            $impostos[$item->codigo_produto]->cst   = $rawcost['cst'];
            $impostos[$item->codigo_produto]->icms  = ($rawcost['c_icms'] == 'NULL')? 0 : str_replace('.',',',$rawcost['c_icms']);
            $impostos[$item->codigo_produto]->ipi   = ($rawcost['c_ipi'] == 'NULL')? 0 : str_replace('.',',',$rawcost['c_ipi']);
            $impostos[$item->codigo_produto]->st    = ($rawcost['c_st'] == 'NULL')? "Não possui" : str_replace('.',',',$rawcost['c_st']);
        endforeach;

        if($unidade_info->pe == 1):
            $find = UnidadePolicom::findFirstBySigla('PE');
            $unidade_info->pe = $find->info;
            unset($find);
        endif;

        if($unidade_info->po == 1):
            $find = UnidadePolicom::findFirstBySigla('PO');
            $unidade_info->po = $find->info;
            unset($find);
        endif;

        if($unidade_info->pp == 1):
            $find = UnidadePolicom::findFirstBySigla('PP');
            $unidade_info->pp = $find->info;
            unset($find);
        endif;

        $impostos_fixos = new stdClass;
        $impostos_fixos->pis = ProdutoImpostoFixo::findFirstByNome('pis');
        $impostos_fixos->pis = str_replace('.',',',$impostos_fixos->pis->total);
        $impostos_fixos->cofins = ProdutoImpostoFixo::findFirstByNome('cofins');
        $impostos_fixos->cofins = str_replace('.',',',$impostos_fixos->cofins->total);
        $impostos_fixos->ircsll = ProdutoImpostoFixo::findFirstByNome('ir/csll');
        $impostos_fixos->ircsll = str_replace('.',',',$impostos_fixos->ircsll->total);

        foreach($orcamento as $item):
            $empresa->tipo      = json_decode($item->fator, true);
            $empresa->tipo      = str_replace('_',' ',$empresa->tipo['tipo_fiscal']);
            $empresa->tipo      = ucwords(rtrim(ltrim(str_replace('custo','',$empresa->tipo))));
            $empresa->razao     = $item->razao_social;
            $empresa->cnpj      = $item->cnpj_cpf;
            $empresa->destino   = $item->destino;
            $empresa->data      = date("d/m/Y", strtotime($item->updated_at));
            $empresa->validade  = date("d/m/Y", strtotime($item->updated_at.' +5 days'));
        endforeach;
        
        $this->view->orcamento=$orcamento;
        $this->view->owner=$owner;
        $this->view->vendedor=$vendedor;
        $this->view->empresa=$empresa;
        $this->view->unidade_info=$unidade_info;
        $this->view->origem = $origem_estado;
        $this->view->impostos = $impostos;
        $this->view->impostos_fixos = $impostos_fixos;

        unset($this->tag);

        require_once APP_PATH . '/vendor/autoload.php';

        $stylesheet_01 = file_get_contents('vendor/bootstrap-3.3.7-dist/css/bootstrap.min.css'); 
        $stylesheet_02 = file_get_contents('vendor/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css');

        $mpdf = new \Mpdf\Mpdf(['tempDir' => APP_PATH . '/cache/mpdf','margin_top'=>7,'margin_bottom'=>7]);
        
        // you may change template file here
        $html = $this->view->getRender('orcamento',
                'createpdf',
                $this->view->orcamento_id = $orcamento_id);

        $mpdf->SetTitle($file_name);
        $mpdf->SetHTMLFooter('
        <table width="100%">
            <tr>
                <td width="33%" style="font-size:8px;">
                    {DATE j-m-Y}
                </td>
                <td width="33%" align="center" style="font-size:8px;">
                    Página {PAGENO} de {nbpg}
                </td>
                <td width="33%" style="text-align: right; font-size:8px;">
                    Proposta Comercial '.$orcamento_id.'
                </td>
            </tr>
        </table>');

        $mpdf->WriteHTML($stylesheet_01,\Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($stylesheet_02,\Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
        $mpdf->Output($file_name,'I');
    }

    public function reportAction($year)
    {
        if ($this->session->auth['role'] == 'administrador') :
            $a=1;
            $month_array = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
            $sum_array = [];
            $total_year = 0;
            $years_arr = range(date('Y'), 2016);

            if ($year != 0 and $year != date('Y')) :
                $month_array = $month_array;
                foreach ($month_array as $key => $value) :
                    $query = "SELECT total FROM Orcamento WHERE data_de_criacao BETWEEN '".$year."-".$a."-1 00:00:00' AND '".$year."-".$a."-31 23:59:59'";
                    $query_result = $this->modelsManager->createQuery($query);
                    $query_execute = $query_result->execute();
                    $sum_array[$a] = 0;
                    foreach ($query_execute as $total) :
                        $sum_array[$a] += floatval(str_replace(",", ".", str_replace(".", "", $total->total)));
                        $total_year += floatval(str_replace(",", ".", str_replace(".", "", $total->total)));
                    endforeach;
                    $sum_array[$a] = number_format($sum_array[$a], 2, ',', '.');

                    $result_array[$a]['month']=$month_array[$a-1];
                    $result_array[$a]['total']=$sum_array[$a];

                    $a++;
                endforeach;

                $total_year = number_format($total_year, 2, ',', '.');

                $this->view->total_year=$total_year;
                $this->view->result_array=$result_array;
                $this->view->month_array=$month_array;

                if ($this->router->getActionName() == 'reporthome') :
                    return $result_array;
                endif;

            else :
                $month_array = array_slice($month_array, 0, date('m'));
                foreach ($month_array as $key => $value) :
                    $query = "SELECT total FROM Orcamento WHERE data_de_criacao BETWEEN '".$year."-".$a."-1 00:00:00' AND '".$year."-".$a."-31 23:59:59'";
                    $query_result = $this->modelsManager->createQuery($query);
                    $query_execute = $query_result->execute();
                    $sum_array[$a] = 0;
                    foreach ($query_execute as $total) :
                        $sum_array[$a] += floatval(str_replace(",", ".", str_replace(".", "", $total->total)));
                        $total_year += floatval(str_replace(",", ".", str_replace(".", "", $total->total)));
                    endforeach;
                    $sum_array[$a] = number_format($sum_array[$a], 2, ',', '.');

                    $result_array[$a]['month']=$month_array[$a-1];
                    $result_array[$a]['total']=$sum_array[$a];

                    $a++;
                endforeach;

                $total_year = number_format($total_year, 2, ',', '.');

                $this->view->total_year=$total_year;
                $this->view->result_array=$result_array;
                $this->view->month_array=$month_array;

                if ($this->router->getActionName() == 'reporthome') :
                    return $result_array;
                endif;
            endif;

            /* SELECT * FROM orcamento WHERE data_de_criacao BETWEEN '2018-1-1 00:00:00' AND '2018-4-1 23:59:59' */
            $this->view->current_year=$year;
            $this->view->years=$years_arr;
        else :
            return $this->response->redirect('/');
        endif;
    }

    public function reporthomeAction()
    {
        if ($this->session->auth['role'] == 'administrador') :
            $every_year= range(date('Y'), 2016);
            $years_arr = range(date('Y'), date('Y')-2);
            $years_arr = array_reverse($years_arr);
            $month_array = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];

            $last_years_result = [
                date('Y')-2 =>$this->reportAction(date('Y')-2),
                date('Y')-1 =>$this->reportAction(date('Y')-1),
                date('Y')       =>$this->reportAction(date('Y'))
            ];

            $a=count($last_years_result[date('Y')]);

            while ($a <= 11) {
                $a++;
                $last_years_result[date('Y')][$a]['month']=$month_array[$a-1];
                $last_years_result[date('Y')][$a]['total']='N/A';
            }

            //Array para montar a tabela é melhor:
            //$result_array= [1=>[month=>"Janeiro",2017=>X,2018=>Y,2019=>Z]...];
            $a=1;
            foreach ($month_array as $key => $value) :
                $result_array[$a]=['month'=>$value,
                $years_arr[0]=>$last_years_result[$years_arr[0]][$a]['total'],
                $years_arr[1]=>$last_years_result[$years_arr[1]][$a]['total'],
                $years_arr[2]=>$last_years_result[$years_arr[2]][$a]['total']];
                $a++;
            endforeach;

            $this->view->years=$every_year;
            $this->view->result_array=$result_array;
            $this->view->last_years=[date('Y')-2,date('Y')-1,date('Y')];
        else :
            return $this->response->redirect('/');
        endif;
    }
}
