<?php
include_once dirname(__FILE__).'/../../app/views/elements/money_format_alt.php';

use App\Services\CartService;
use App\Services\ProdutoCoreService;
use Phalcon\Http\Response;

class CartController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Cart');
        parent::initialize();
    }
    public function indexAction() {
        $user_id = intval($this->session->auth['id']);
        $cart_set = new CartService;
        $cart_set = $cart_set->cartIndex($user_id);
        $total = null;

        foreach($cart_set as $item):
            $total += $item->valor_unitario * $item->quantidade;
        endforeach;

        //dd($total);
        $this->view->total = $total;
        $this->view->cart = $cart_set;
    }
    
    public function addAction($codigo_produto,$quantidade,$redirect,$concat=0) {
        $user_id = intval($this->session->auth['id']);
        $cart = new CartService;

        if($cart->beforeAdd($user_id,$codigo_produto) AND $quantidade == 0 AND $concat == 1):
            $this->removeAction($codigo_produto,$redirect);
        endif;

        if($cart->beforeAdd($user_id,$codigo_produto) AND $quantidade > 0):
            if($cart->update($user_id,$codigo_produto,$quantidade,$concat) == TRUE):
                $this->flash->success('Carrinho atualizado');
            else:
                $this->flash->success('Escolha uma quantidade maior ou menor para adicionar');
            endif;
        endif;

        if(!$cart->beforeAdd($user_id,$codigo_produto) AND $quantidade > 0):
            $cart->add($user_id,$codigo_produto,$quantidade);
            $this->flash->success('Produto adicionado ao carrinho');
        endif;

        //echo $cart->beforeAdd($user_id,$codigo_produto);
        $this->response->redirect(str_replace('**','/',$redirect));
    }
    public function removeAction($codigo_produto,$redirect) {
        $user_id = intval($this->session->auth['id']);
        $cart = new CartService;
        
        $cart->remove($user_id,$codigo_produto);
        
        $this->flash->error('Produto removido do carrinho');
        $this->response->redirect(str_replace('**','/',$redirect));
    }

    public static function infoAction($user_id,$codigo_produto){
        if(!isset($user_id)):
            return FALSE;
        endif;

        if(isset($user_id) and $codigo_produto == 0):
            return CartService::cartHeaderInfo($user_id);
        endif;
        
        if(isset($user_id) and $codigo_produto > 0):
            return CartService::cartProdInfo($user_id,$codigo_produto);
        endif;
    }

    public function saveAction(){
        //TODO - envia o e-mail após salvar.

        // Tipo == 0 = Salvo : 1 = Confirmar pedido de compra

        /*
        if(empty($this->request->getPost("submit_type"))):
            $this->response->redirect('/testttt');
        endif;
        */

        if($this->request->getPost("submit_type") == "EMPTY"):
            $this->flash->error("Por favor, selecione comprar ou salvar orçamento.");
            $this->view->error = TRUE;
    		return $this->dispatcher->forward(["controller" => "cart","action" => "index"]);
        endif;

        if($this->request->getPost("submit_type") !=  "EMPTY"):
            $user_id = $this->session->auth['id'];
            $submit_type = $this->request->getPost("submit_type");
            $obs = $this->request->getPost("obs");
            $total=0;

            $user_vars = ViewUsuarioClienteCustoVars::findFirstById($user_id);

            //Calcula valor total
            $cart_set = new CartService;
            $cart_set = $cart_set->cartIndex($user_id);

            foreach($cart_set as $item):
                $total += $item->valor_unitario * $item->quantidade;
            endforeach;

            $total = number_format($total,2,',','.');
            
            

            $orcamento = new Orcamento;
            $orcamento->usuario_id  = intval($user_id);
            $orcamento->status      = "Salvo";
            $orcamento->observacao  = $obs;
            $orcamento->total       = strval($total);
            $orcamento->ucode       = $user_vars->clienteUcode;
            $orcamento->tabela      = $user_vars->tabela_de_custo;
            $orcamento->tipo        = intval($submit_type);
            if(count($cart_set) == 0):
                $this->flash->error("Seu carrinho expirou ou já foi processado.");
                return $this->dispatcher->forward(["controller" => "cart","action" => "index"]);
            else:
                $orcamento->save();
            endif;

            //dd($orcamento);
            
            //Insere produtos no orcamento_item
            foreach($cart_set as $item):
                $forma_preco = ProdutoCoreService::formaPreco($user_id,$item->codigo_produto);
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

                $orcamento_item = new OrcamentoItem;
                $orcamento_item->orcamento_id   = $orcamento->id;
                $orcamento_item->produto_id     = 1;
                $orcamento_item->codigo_produto = $item->codigo_produto;
                $orcamento_item->quantidade     = $item->quantidade;
                $orcamento_item->unitario       = number_format($item->valor_unitario,2,',','.');
                $orcamento_item->subtotal       = number_format($item->valor_unitario * $item->quantidade,2,',','.');
                $orcamento_item->rawcost        = $taxas;
                $orcamento_item->fator          = $cost_vars;
                $orcamento_item->markup         = $user_vars->tabela_de_custo.".".$forma_preco->mark_up;
                if(count($cart_set) == 0):
                    $this->flash->error("Seu carrinho expirou ou já foi processado.");
                    return $this->dispatcher->forward(["controller" => "cart","action" => "index"]);
                else:
                    $orcamento_item->save();
                endif;
            endforeach;
            
            

            //Remove itens do orcamento_item_temp
            $query_update = "UPDATE OrcamentoItemTemp SET open = 0 WHERE user_id = $user_id AND open=1";
		    $result_query_update = $this->modelsManager->createQuery($query_update);
            $result_query_update->execute();

            $this->flash->success("Muito obrigado! Seu orçamento será processado por nossa equipe.");
            $this->response->redirect('/orcamento/index');
        endif;
    }
    public function sendConfirmationEmailAction($send_email_vars)
    {
        $host=$_SERVER['HTTP_HOST'];
        $to = $send_email_vars['email'];

        $htmlContent = file_get_contents(APP_PATH ."app/views/cart/email.volt");

        if ($send_email_vars['cliente_ucode'] != 'NULL'){
            $seus_dados = "<b>Seu e-mail:</b> ".$send_email_vars['email']."<br>".
                "Nome da empresa: ".$send_email_vars['cliente_nome']."<br>".
                "CPF ou CNPJ: " . $send_email_vars['cliente_cnpj_cpf']. "<br>".
                "Código GP Cabling: ". $send_email_vars['cliente_ucode'];
        }
        else{
            $seus_dados = "<b>Seu e-mail:</b> ".$send_email_vars['email']."<br>".
                "Usuário não associado à empresa no momento deste orçamento.";
        }

        if ($send_email_vars['vend_name']=='NULL') {
            $vendedor="Sem vendedor. Nossa equipe irá designar um vendedor para atendê-lo.<br>";
        }
        else{
            $vendedor = "<b>Seu vendedor segue em cópia neste e-mail</b><br>".$send_email_vars['vend_name'].' - '.$send_email_vars['vend_email'];
        }

        if($send_email_vars['orc_total'] != '0,00' AND $this->session->auth['status'] == 'active'
            AND $this->session->auth['custo_pagina'] AND $this->session->auth['client'] != null
            AND $orcamento->ucode != null AND $orcamento->tabela != null){
            $resumo_orcamento = "<b>Total orçamento:</b> R&#36;".$send_email_vars['orc_total'];
        }
        else{
            $resumo_orcamento = "Acesse seu painel para visualizar seu orcamento.";
        }


        //$resumo_orcamento="";
        $htmlContent = preg_replace('[{{orc_id}}]',$send_email_vars['orc_id'], $htmlContent);
        $htmlContent = preg_replace('[{{seus_dados}}]',$seus_dados, $htmlContent);
        $htmlContent = preg_replace('[{{vendedor}}]',$vendedor, $htmlContent);
        $htmlContent = preg_replace('[{{lista_prod}}]',$send_email_vars['lista_prod'], $htmlContent);
        $htmlContent = preg_replace('[{{orc_total}}]',$resumo_orcamento, $htmlContent);
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $mailer = $this->di['mailer'];
        $mailer->CharSet = 'UTF-8';
        $mailer->addAddress($send_email_vars['email'], $send_email_vars['name']);

        if ($send_email_vars['vend_name']!='NULL') {
            $mailer->addAddress($send_email_vars['vend_email'], $send_email_vars['vend_name']);
        }

        $mailer->AddBCC('sandro@gpcabling.com.br');
        $mailer->AddBCC('luisfernando@gpcabling.com.br');
        //$mailer->AddBCC('anderson@gpcabling.com.br');

        $ucode = $send_email_vars['cliente_ucode'];
        $ucode = Cliente::findFirstByUCode($ucode);
        $unidade_policom = $ucode->unidade_policom;

        if ($unidade_policom != null) {
            switch ($unidade_policom) {
                case 'PO':
                    $mailer->AddBCC('alexandre@gpcabling.com.br');
                    $mailer->AddBCC('po@gpcabling.com.br');
                    break;

                case 'PA':
                    $mailer->AddBCC('nei@pariscabos.com.br');
                    $mailer->AddBCC('cristiano@pariscabos.com.br');
                    $mailer->AddBCC('pa@gpcabling.com.br');
                    break;

                case 'PR':
                    $mailer->AddBCC('eduardo@gpcabling.com.br');
                    $mailer->AddBCC('joaocarlos@gpcabling.com.br');
                    $mailer->AddBCC('pr@gpcabling.com.br');
                    break;

                case 'PC':
                    $mailer->AddBCC('freddy@gpcabling.com.br');
                    $mailer->AddBCC('pc@gpcabling.com.br');
                    break;

                default:
                    # code...
                    break;
            }
        }


        // //Set the subject line
        if($send_email_vars['tipo'] == 1){
            $mailer->Subject = "★ Orçamento Salvo - Pedido de Compra - ".$send_email_vars['orc_id'];
        }
        else{
            $mailer->Subject = "Orçamento Salvo - ".$send_email_vars['orc_id'];
        }


        // //Read an HTML message body from an external file, convert referenced images to embedded,
        // //convert HTML into a basic plain-text alternative body
        $mailer->msgHTML($htmlContent);

        // var_dump($mailer);
        if (!$mailer->send()) {
            // var_dump("Mailer Error: " . $mail->ErrorInfo) ;
        } else {
            // var_dump("Message sent!");
        }
    }
}
