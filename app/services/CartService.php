<?php
namespace App\Services;

use OrcamentoItemTemp;
use Phalcon\Di;
use ProdutoCore;
use stdClass;

class CartService{
    
    public function __construct()
    {
        $this->di = Di\FactoryDefault::getDefault();
    }

	public function initialize()
	{
		parent::initialize();
    }
    
    public function beforeAdd($user_id,$codigo_produto,$open=1){
        $find = OrcamentoItemTemp::findFirst([
            "conditions"=>"user_id='".$user_id."'"."and codigo_produto ='".$codigo_produto."'"."and open ='".$open."'"
        ]);
        
        return $find->id;
    }

    public function add($user_id,$codigo_produto,$quantidade,$open=1){
        $add = new OrcamentoItemTemp();
        $price = new ProdutoCoreService;
        $price = $price->formaPreco($user_id,$codigo_produto);

        $add->user_id           = $user_id;
        $add->codigo_produto    = $codigo_produto;
        $add->quantidade        = $quantidade;
        $add->valor_unitario    = $price->price;
        $add->open              = $open;
        $add->save();
    }

    public function update($user_id,$codigo_produto,$quantidade,$concat=0,$open=1){
        $return_value=FALSE;
        
        $find = OrcamentoItemTemp::findFirst([
            "conditions"=>"user_id='".$user_id."'"."and codigo_produto ='".$codigo_produto."'"."and open ='".$open."'"
        ]);

        if($concat == 1 AND $find->quantidade != $quantidade):
            $find->quantidade = $quantidade;
            $find->save();
            $return_value = TRUE;
        endif;

        if($concat == 0):
            $find->quantidade += $quantidade;
            $find->save();
            $return_value = TRUE;
        endif;

        return $return_value;
    }

    public function remove($user_id, $codigo_produto, $open=1){
        $find = OrcamentoItemTemp::findFirst([
            "conditions"=>"user_id='".$user_id."'"."and codigo_produto ='".$codigo_produto."'"."and open ='".$open."'"
        ]);

        $find->open = 0;
        $find->save();
    }

    public function cartIndex($user_id,$open=1){
        $find = OrcamentoItemTemp::find([
            "conditions"=>"user_id='".$user_id."'"."and open ='".$open."'"
        ]);
        
        $tipo_fiscal = $this->di->getModelsManager()
                    ->createBuilder()
                    ->columns('Usuario.id,
                        Usuario.codigo_cliente,
                        ClienteCore.clienteUcode,
                        ClienteCore.tipo_fiscal,
                        ClienteTipoFiscal.id,
                        ClienteTipoFiscal.tipo_fiscal as tipo_fiscal')
                    ->from('Usuario')
                    ->join('ClienteCore','Usuario.codigo_cliente = ClienteCore.clienteUcode')
                    ->join('ClienteTipoFiscal','ClienteCore.tipo_fiscal = ClienteTipoFiscal.id')
                    ->where('Usuario.id = :user_id:',["user_id"=>$user_id])
        ;

        $tipo_fiscal = $tipo_fiscal->getQuery()->execute();

        foreach($tipo_fiscal as $type){
            $tipo_fiscal = $type->tipo_fiscal;
        }

        //dd($tipo_fiscal);

        $builder = $this->di->getModelsManager()
                    ->createBuilder()
                    ->columns('OrcamentoItemTemp.codigo_produto as codigo_produto,
                        OrcamentoItemTemp.quantidade as quantidade,
                        OrcamentoItemTemp.valor_unitario as valor_unitario,
                        ProdutoCore.id as id,
                        ProdutoCore.descricao_sys as descricao_sys,
                        ProdutoCore.descricao_site as descricao_site,
                        ProdutoCore.ncm as ncm')
                    ->from('OrcamentoItemTemp')
                    ->join('ProdutoCore','OrcamentoItemTemp.codigo_produto = ProdutoCore.codigo_produto')
                    ->where('OrcamentoItemTemp.open = 1')
                    ->orderby('OrcamentoItemTemp.id ASC')
                    ->andwhere('OrcamentoItemTemp.user_id = :user_id:',["user_id"=>$user_id]);

        $total = 0;
        $cart_set=[];

        $builder=$builder->getQuery()->execute();

        foreach ($builder as $item):
            $descricao = ($item->descricao_site == 'NULL')? $item->descricao_sys : $item->descricao_site;
            $forma_preco = ProdutoCoreService::formaPreco($user_id,$item->codigo_produto);

            $cart_set[$item->codigo_produto]= new stdClass();
            $cart_set[$item->codigo_produto]->quantidade        = $item->quantidade;
            $cart_set[$item->codigo_produto]->id                = $item->id;
            $cart_set[$item->codigo_produto]->codigo_produto    = $item->codigo_produto;
            $cart_set[$item->codigo_produto]->descricao         = $descricao;
            $cart_set[$item->codigo_produto]->valor_unitario    = $item->valor_unitario;
            $cart_set[$item->codigo_produto]->tipo_fiscal       = $tipo_fiscal;
            $cart_set[$item->codigo_produto]->origem            = $forma_preco->origem;
            $cart_set[$item->codigo_produto]->ncm               = $item->ncm;
            $cart_set[$item->codigo_produto]->cst               = $forma_preco->taxas["cst"];
            $cart_set[$item->codigo_produto]->icms              = ($forma_preco->taxas["c_icms"] == 'NULL') ? 0 : $forma_preco->taxas["c_icms"];
            $cart_set[$item->codigo_produto]->ipi               = ($forma_preco->taxas["c_ipi"]  == 'NULL') ? 0 : $forma_preco->taxas["c_ipi"];
            $cart_set[$item->codigo_produto]->st                = ($forma_preco->taxas["c_st"]   == 'NULL') ? 0 : $forma_preco->taxas["c_st"];
        endforeach;

        //dd($cart_obj);

        return $cart_set;
    }

    public static function cartHeaderInfo($user_id,$open=1){
        $items = 0;
        
        $find = OrcamentoItemTemp::find([
            "conditions"=>"user_id='".$user_id."'"."and open ='".$open."'"
        ]);
        
        foreach($find as $item):
            $items += $item->quantidade;
        endforeach;

        return $items;
    }

    public static function cartProdInfo($user_id,$codigo_produto,$open=1){
        $find = OrcamentoItemTemp::findFirst([
            "conditions"=>"user_id='".$user_id."'"."and codigo_produto ='".$codigo_produto."'"."and open ='".$open."'"
        ]);
        
        $return_value = ($find)? $find->quantidade:null;
        
        return $return_value;
    }
}