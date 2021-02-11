<?

namespace App\Services;


use ViewUsuarioClienteCustoVars;
use ProdutoCore;
use UnidadePolicom;

class ProdutoCoreService
{
	public static function formaPreco($user_id,$codigo_produto)
	{
		$cliente_user_vars = ViewUsuarioClienteCustoVars::findFirstById($user_id);
		$produto = ProdutoCore::findFirstByCodigoProduto($codigo_produto);

		if($cliente_user_vars and $produto->status == 1 and $produto->peep != 1):
			$mark_up = $cliente_user_vars->mark_up_fabricantes;
			$mark_up = ($mark_up == 'NULL') ? $cliente_user_vars->mark_up_geral : json_decode($cliente_user_vars->mark_up_fabricantes,true);
			$mark_up = (isset($mark_up[$produto->sigla_fabricante])) ? $mark_up[$produto->sigla_fabricante] : "0".$cliente_user_vars->mark_up_geral;

			$produtoUcode  = $codigo_produto.strtolower($produto->origem);


			$tabela_de_custo = str_replace(" ","", ucwords(str_replace("_", " ", $cliente_user_vars->tabela_de_custo)));

			//Coluna do preço
			$coluna = $cliente_user_vars->custo_sys_string.$mark_up;

			//Coluna das taxas
			$coluna_taxas = rtrim(str_replace("custo_","taxas_",$cliente_user_vars->custo_sys_string),'_');

			$price = $tabela_de_custo::findFirstByProdutoUcode($produtoUcode);
			$price->price = ($price->$coluna == 0)? 'Sob Consulta' : $price->$coluna;
			$price->taxas = json_encode($price->$coluna_taxas);
			$price->taxas = json_decode($price->$coluna_taxas,true);
			$price->mark_up = $mark_up;
			//workaround - leave as is
			$price->origem_empresa = $price->origem;

			$origem = UnidadePolicom::findFirstBySigla($price->origem);
			$price->origem = $origem->estado;
		else:
			$price->price= 'Sob Consulta' ;
		endif;

		return $price;
	}

	/**
	 * @param array[ProdutoCore] $products
	 * @return array[array[string]] Links de documentos agrupados por produto.
	*/
	public static function getDocuments($products = [])
	{
		$documents = [];

		foreach ($products as $product) {
			$document_files = array_splice(
				scandir("../public/produto_documento/" . $product->codigo_produto . "/"),
				2
			);

			if (!count($document_files)) {
				continue;
			}

			$documents[$product->codigo_produto] = [];

			foreach ($document_files as $document) {
				array_push($documents[$product->codigo_produto], [
					"filename" => $document,
					"path" => stripcslashes("\/produto_documento/") . $product->codigo_produto . "/" . $document
				]);
			}
		}
		return $documents;
	}
}