<?php

use App\Services\ProdutoCategoriaEstruturaService;
use App\Services\ProdutoCoreService;
use ProdutoFabricante;

class FabricanteController extends ControllerBase {
	public function initialize(){
		$this->tag->setTitle('Fabricante');
		parent::initialize();
	}
	
	/**
	 * @param null|string $sigla
	 * @param null|integer $categoria
	 * @param null|integer $subcategoria
	 */
	public function indexAction($sigla = null, $categoria = null, $subcategoria = null)
	{
		try {
			if (!$this->request->isPost()) 
			{ 
				if(is_null($sigla))
				{
					$this->view->fabricante_full = ProdutoFabricante::find(['status = 1', 'order' => 'sigla']);
				} else {
					
					$fabricante = ProdutoFabricante::findFirstBySigla($sigla); 
		
					if (!$fabricante || $fabricante->status == ProdutoFabricante::FABRICANTE_INATIVO)
					{
						$this->flash->error("Fabricante Inexistente.");
						return $this->dispatcher->forward(
							[
								"controller" => "index",
								"action"	 => "index",
							]
						);
					}

					$this->view->setVar('fabricante', $fabricante);

					$query = "SELECT 
						pc.codigo_produto, 
						pc.descricao_site, 
						pc.descricao_sys,
						pc.ref,
						pc.unidade_venda,
						pc.moeda_venda,
						pc.tipo_fiscal,
						pc.promo,
						pce.id,
						pce.nome,
						pe.total_estoque as total_estoque
					FROM produto_core pc 
					JOIN produto_categoria_item pci on pci.codigo_produto = pc.codigo_produto 
					JOIN produto_categoria_estrutura pce on pce .id = pci.id_produto_categoria_estrutura 
					JOIN produto_estoque pe on pe.codigo_produto = pc.codigo_produto
					WHERE pc.sigla_fabricante = '{$sigla}' AND pc.status = 1";

					if(!is_null($categoria))
						$query .= " AND pce.parent = '{$categoria}'";
					else
						$query .= " AND pce.parent = 1";

					$query .= " ORDER BY total_estoque DESC, tipo_fiscal DESC";

					$produtos = Phalcon\Di::getDefault()->get('db')->query($query)->fetchAll(\Phalcon\Db::FETCH_OBJ);
					
					$this->view->setVar('produto',$produtos);
					$categorias = [];

					//Pegar descrições dos produtos
					foreach ($produtos as $item) {

						$categorias[$item->id] = (object) array(
							'id' 		=> $item->id,
							'nome'		=> $item->nome
						);

						//Pega primeira imagem
						$directory="../public/produto_imagem/".$item->codigo_produto."/";
						$files = scandir($directory);
						$pd = "";

						if (strlen($files[2]) > 2) {
							$img = "/produto_imagem/{$item->codigo_produto}/{$files[2]}";

							$image_size = GetImageSize($directory.$files[2]);

							$big_side 	= ($image_size[0] > $image_size[1]) ? $image_size[0] : $image_size[1];
							$small_side = ($image_size[0] > $image_size[1]) ? $image_size[1] : $image_size[0];

							$sizePadding = round((95 - round(($small_side * 95)/$big_side, 2))/2);
							
							$pd = ($image_size[0] < $image_size[1]) ? "style='padding:0 {$sizePadding}px;'" : "style='padding:".abs($sizePadding).'px 0;"'; 
							
						} else {
							$img = "/img/no-image-placeholder.png";
						}
						
						$thumb[$item->codigo_produto]	=	$img;
						$padding[$item->codigo_produto]	=	$pd;
					}

					$this->view->thumb 		= $thumb;
					$this->view->padding 	= $padding;

					$this->view->auth = $this->session->get('auth');

					if(!$this->session->get('categorias') || $sigla !==  $this->session->get('fabricante')) {
				
						$this->session->set('fabricante', $sigla);

						$this->session->set('categorias', $categorias);
					}
						

					$this->view->categorias = $this->session->get('categorias');
					$this->view->sigla = $sigla;
					$this->view->categoriaFabricante = $categoria;

					if(!is_null($categoria)) {
						$service = new ProdutoCategoriaEstruturaService;
						$data = $service->getPageList($categoria, $subcategoria, $sigla);
	
						foreach($data['page']->items as $key => $produtoCore) {
							if($produtoCore->status == 0) {
								unset($data['page']->items[$key]);
							}
						}
						
						$this->view->setVar('produto',$data['page']->items);
						$this->view->subcategories = $data['subcategories'];
					}	

					//Começa o find de produto por produto. E definição da variável price.
					if (isset($this->session->auth) && isset($this->session->auth['id']) && $this->session->auth['custo_pagina'] != 0) {
						$price 	= [];
						$origem = [];
						$forma_preco = new ProdutoCoreService;

						foreach ($produtos as $item){
							$preco							= 	$forma_preco->formaPreco($this->session->auth['id'],$item->codigo_produto);
							$price[$item->codigo_produto]	= 	$preco->price;
							$origem[$item->codigo_produto]	= 	$preco->origem;
							$icms[$item->codigo_produto] 	=  	($preco->taxas['c_icms']!='NULL') ? $preco->taxas['c_icms'] : 0 ;
						}

						$this->view->setVar('price',	$price);
						$this->view->setVar('origem', 	$origem);
						$this->view->setVar('icms', 	$icms);
					}
				}
			} else {
				echo "Fabricante não existe";
			}
		} catch  (\Exception $e) {
			echo get_class($e), ": ", $e->getMessage(), "\n";
			echo " File=", $e->getFile(), "\n";
			echo " Line=", $e->getLine(), "\n";
			echo $e->getTraceAsString();}
		}
}