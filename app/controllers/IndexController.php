<?php

use App\Services\ProdutoCoreService;

class IndexController extends ControllerBase
{
	public function initialize()
	{
		$this->tag->setTitle('Home');
		parent::initialize();


		if (!isset($this->session->cart)) {
			$this->session->set('cart', array());
		}
	}

	public function indexAction()
	{
		$builder = $this->di->getModelsManager()
		->createBuilder()
		->columns('ProdutoCore.codigo_produto,ProdutoCore.descricao_sys,ProdutoCore.descricao_site')
		->from('ProdutoCore')
		->where('ProdutoCore.promo= 1')
		->andwhere('ProdutoCore.status=1')
		->limit(16);

		$produto = $builder->getQuery()->execute();

		//TODO - Arrumar a imagem em função para view - ajustar no ProdutoCoreServicce
		foreach ($produto as $item):
			$image_directory		="../public/produto_imagem/".$item->codigo_produto."/";
			$image_files 			= scandir($image_directory);
			$image_files 			= array_splice($image_files, 2);

			$produto_img[$item->codigo_produto] = ($image_files[0] == null) ? "/img/no-image-placeholder.png" : "/public/produto_imagem/".$item->codigo_produto."/" . $image_files[0];
		endforeach;

		$this->view->produto_img=$produto_img;
		$this->view->produto_documents = ProdutoCoreService::getDocuments($produto);
		$this->view->produto=$produto;
	}
}
