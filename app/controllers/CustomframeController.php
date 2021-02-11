<?php
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Manager;
use Phalcon\Mvc\Model\Resultset;

class CustomframeController extends Controller{
	public function initialize(){
		$this->tag->setTitle('');
		//parent::initialize();
	}
	
public function indexAction($nome=null){

try{
	if (!$this->request->isPost()) {
	if($nome==null){
	$this->flash->error("Custom Frame não pode ser nulo");
	return  $this->response->redirect('/cotaonline');}

	else{
	//Busca o Custom frame pelo nome
	$customframe=CustomFrame::findFirstByNome($nome);

	//Faz verificação da data de expiração
	$end_date 	= $customframe->end_date;
	$ts = new \DateTime();
	$today = $ts->format('Y-m-d');

	//Se não for custom e expirar, vai para o Custom Frame 'padrao_basico'
	if($end_date<$today AND $end_date != NULL AND $end_date != '1000-01-01' AND $customframe->custom != 1){
		$nome = 'padrao_basico';
		$customframe=CustomFrame::findFirstByNome($nome);
	}

	//Se não for custom e expirar, vai para a home
	if($end_date<$today AND $end_date != NULL AND $end_date != '1000-01-01' AND $customframe->custom == 1){
		$this->flash->error("Custom Frame Expirado");
		return  $this->response->redirect('/cotaonline');
	}
	
	//Verifica se o Custom Frame existe e envia para a página inicial caso não exista.
	if (!$customframe){
		$this->flash->error("Custom Frame Inexistente.");
		return  $this->response->redirect('/cotaonline');}
	
	//Conteúdo do Custom 
	else{
	//Pega os settings do Custom Frame prepara variável para o switch
	//Opções - 10 - 01 - 00
	$custom_settings = $customframe->custom.$customframe->aleatorio_por_fabricante;
	if($custom_settings =='10'){if($customframe->custom_content == '' OR $customframe->custom_content == NULL){$custom_settings=FALSE;}}
	if($custom_settings =='01'){if($customframe->produtos == '' OR $customframe->produtos == NULL){$custom_settings=FALSE;}}
	if($custom_settings =='00'){if($customframe->produtos == '' OR $customframe->produtos == NULL){$custom_settings=FALSE;}}

	$produtos_custom_frame = json_decode($customframe->produtos, true);

	switch ($custom_settings) {
		//Custom Content
		case '10':
		$settings = '10';
		$conteudo = $customframe->custom_content;
		break;

		//Aleatório por fabricante
		case '01':
		$settings = '01';
		if (is_array($produtos_custom_frame['fabricante']) AND key($produtos_custom_frame) == 'fabricante') {
		$sigla_fabricante = strtolower($produtos_custom_frame['fabricante'][0]);
		$limite = intval($produtos_custom_frame['fabricante'][1]);

		/* A query no mysql para randômico
		SELECT id,codigo_produto,descricao_sys,status,total_estoque FROM produto_core
		WHERE sigla_fabricante = 'VL' AND status = 1 AND total_estoque > 0 ORDER BY RAND() LIMIT 3;
		*/

		$produtos_aleatorios = ProdutoCore::find(["conditions" =>"sigla_fabricante = ?0 AND status= 1
			AND total_estoque > 0 ORDER BY RAND() LIMIT $limite","bind"=>[
            0 => $sigla_fabricante,]]);

		$produtos_aleatorios_array = [];

		foreach ($produtos_aleatorios as $produto) {
			//Pega a primeira imagem
			$image_directory	="../public/produto_imagem/".$produto->codigo_produto."/";
			$image_files 			= scandir($image_directory);
			$image_files 			= array_splice($image_files, 2);

			if(!$image_files){$image_files[0]='/img/no-image-placeholder.png';}
			else{$image_files[0]="/produto_imagem/".$produto->codigo_produto.'/'.$image_files[0];}

			$produtos_aleatorios_array[$produto->codigo_produto]['codigo_produto'] = $produto->codigo_produto;
			$produtos_aleatorios_array[$produto->codigo_produto]['descricao_sys'] = $produto->descricao_sys;
			$produtos_aleatorios_array[$produto->codigo_produto]['image'] = $image_files[0];
		}

		$conteudo = $produtos_aleatorios_array;
		}
		else{
		$conteudo=FALSE;
		}
		break;
		
		//Custom Frame com produtos
		case '00':
		$settings = '00';
		if (is_array($produtos_custom_frame) AND key($produtos_custom_frame) == 'produto_01') {
		$produtos_custom_frame_default=[];

		foreach ($produtos_custom_frame as $produto) {
			$produtos_custom_frame_default[$produto[0]]['codigo_produto']=$produto[0];
			
			//Pega a primeira imagem
			$image_directory	="../public/produto_imagem/".$produto[0]."/";
			$image_files 			= scandir($image_directory);
			$image_files 			= array_splice($image_files, 2);

			if(!$image_files){$image_files[0]='/img/no-image-placeholder.png';}
			else{$image_files[0]="/produto_imagem/".$produto[0].'/'.$image_files[0];}
			
				//Verifica se o produto possui um nome custom definido no banco de dados.
				if($produto[1] != ""){
				$produtos_custom_frame_default[$produto[0]]['descricao_sys'] = $produto[1];
				}
				//Caso não possua, pega do produto_core
				else{
				$produto_descricao_sys = ProdutoCore::findFirst([
					"columns"=>'descricao_sys',
					"conditions"=>"codigo_produto=?1",
					"bind"=>[1=>$produto[0]]]);
				$produtos_custom_frame_default[$produto[0]]['descricao_sys'] = $produto_descricao_sys->descricao_sys;
				//SHORTHAND IF >> USAR MAIS ISSO!
				$error = (!$produto_descricao_sys->descricao_sys) ? $error_log ++ : FALSE;
				}
				
				$produtos_custom_frame_default[$produto[0]]['image'] = $image_files[0];
		}

		$conteudo = ($produtos_custom_frame_default AND $error_log==0) 
			? $conteudo=$produtos_custom_frame_default 
			: "Erro ao buscar algum dos produtos. Verifique a configuração deste Custom Frame.";
		}

		else{
		$conteudo=FALSE;
		}
		break;

		/*
		ERRO Custom Content está ligado mas custom content está vazio
		ERRO Custom Content está ligado e Aleatório também
		ERRO Aleatório está ligado mas está sem conteúdo em produtos
		ERRO Default está ligado mas está sem conteúdo em produtos
		*/
		default:
		$conteudo = FALSE;
		break;
	}

	//Atualiza o view count. ATENÇÃO AO NOME DA TABELA
	$query_update = "UPDATE CustomFrame SET view_count=view_count+1 WHERE nome='".$nome."'";
	$result_query_update = $this->modelsManager->createQuery($query_update);
	
	//Vai pro view para carregar layout diferente.
	$list = $customframe->list;
	
	if(!$conteudo){$conteudo = 'Custom Frame não está configurado corretamente. Verificar este Custom Frame no banco de dados';}
	else{$execute_query_update = $result_query_update->execute();}

	$this->view->settings = $settings;
	$this->view->date = $date;
	$this->view->today = $today;
	$this->view->list = $list;
	$this->view->conteudo = $conteudo;
	}
}
}

	else{
	echo "Custom Frame não existe";
	}
}

	
	catch  (\Exception $e){
		echo get_class($e), ": ", $e->getMessage(), "\n";
		echo " File=", $e->getFile(), "\n";
		echo " Line=", $e->getLine(), "\n";
		echo $e->getTraceAsString();}
	}
}