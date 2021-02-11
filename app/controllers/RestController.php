<?php

use Phalcon\Crypt;
use Phalcon\Http\Request;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Manager;


class RestController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Rest Test');
        //parent::initialize();
    }

    public function indexAction()
    {
    	//echo 'test';
    }
    
    public function inputAction($string01,$string02,$string03,$string04,$string05,$string06,$string07,$string08,$string09,$string10){
    	$time_start = microtime(true);
    	//http://cotaonline:8080/rest/input/a/b/cuzao/d/e/f/g/h/k/l
    	//echo $string01.'---'.$string02.'---'.$string03.'---'.$string04.'---'.$string05.'---'.$string06.'---'.$string07.'---'.$string08.'---'.$string09.'---'.$string10;
			$phql = "INSERT INTO Cuput
			(string01,string02,string03,string04,string05,string06,string07,string08,string09,string10)
			VALUES ('$string01','$string02','$string03','$string04','$string05','$string06','$string07','$string08','$string09','$string10')";
			$query = $this->modelsManager->createQuery($phql);
			$execute_query= $query->execute();
			
			$time_end = microtime(true);
			$time = $time_end - $time_start;


			echo "DONE\n";
			echo $time;

    }

    public function cyphertextAction(){
    	/* NOT ALLOWED = $*/
	   	/* Create CURL url to cypherput */
			$url_term='';

    	/**
    	Teste com array por string -- otimizado para o cypherput
    	**/
    	/*$secret=[
    	"alçapão caçamba & pernilongo ||",
    	"blindado",
    	"cão CURL TEST 02",
    	"dedilhado",
    	"efusivo",
    	"ferramenta",
    	"gargalo",
    	"hora",
    	"independente",
    	"luminuras"];*/

			/*
			foreach ($secret as $key => $value):
				$crypt = new Crypt();
				$key = "5//ba24cbea853/*d5ba24cbea85/*ssssssssss/**425ba24c//be/K9996a8542";
				$item = $crypt->encryptBase64($value,$key,true);
				$url_term  .= $item.'/';
			endforeach;
			echo "http://cotaonline:8080/rest/cypherput/".$url_term;*/
    	
    	/**
    	Teste com json string
    	**/

    	$secret = '{"table": "DF_TCliCad","rows":[{"uCode": "R0010PR","iCdCodigo": "R0010","sNmNome": "KESSEL ENGENHARIA LTDA","sNmApelido": "KESSELARIA LTDA","sDcTelefone": "","sDcEndereco": "RUA SENADOR DANTAS , 71 COB.01","sDcBairro": "CENTRO","sDcCidade": "RIO DE JANEIRO","sDcEstado": "RJ","sDcCep": "20031200","sDcPais": "BR","sDcCnpjCpf": "","sDcInscEst": "","sDcOrigem": "3","iCdCodRepr": "13","sCdPrefixo": "1","iCdSufixo": "1","sCdEmpresa": "PR","iCdVendedor": "1","sDcComentarios": "CLIENTE FABIO P.7 AVAYA P.6 FURUKAWA fabio luiz- 9976-2446  FAX. 2283-5931 beth@kessel.com.br","dDtCadastro": "20/09/2001","dDtAlteracao": "26/05/2009","sCdPreco": "4","iTpTipo": "1","iCdTransp": "1","iCliAgenda": "0","iCliContato": "1","sDcRG": "","iCdNatureza": "2","sDcSiteEmail": "Nao Possui","sDcLogo": "","iFlStatus": "2","iCdFabr": "0","nVlTotCompra": "0","dDtUltCompra": "09/09/2002","sFlTipo": "C","sUsuAlt": "","sUsuCad": "","iCdVendedor2": "","iCdVendedor3": "","sDcZip": "","sDcCustNumber": "","nVlLimiteCred": "0","nVlMinPedComp": "0","tabela_custo": "custo_rj_rj"}]}';
    	
    	/* Crypt */
    	$crypt = new Crypt();
			//$crypt->setCipher('aes-128-cbc');
			$key = "5//ba24cbea853/*d5ba24cbea85/*ssssssssss/**425ba24c//be/K9996a8542";
			$secret = $crypt->encryptBase64($secret,$key,true);

			echo $secret.'<br><br><br><br>';
			
			$secret=$crypt->decryptBase64($secret,$key,true);
			echo $secret;

			var_dump(json_decode($secret, true));


			echo "<br>";
    }

    public function cypherputAction($string01,$string02,$string03,$string04,$string05,$string06,$string07,$string08,$string09,$string10){
    	$time_start = microtime(true);

			$crypt = new Crypt();
			$key = "5//ba24cbea853/*d5ba24cbea85/*ssssssssss/**425ba24c//be/K9996a8542";
			
			$string01 = $crypt->decryptBase64($string01,$key,true);
			$string02 = $crypt->decryptBase64($string02,$key,true);
			$string03 = $crypt->decryptBase64($string03,$key,true);
			$string04 = $crypt->decryptBase64($string04,$key,true);
			$string05 = $crypt->decryptBase64($string05,$key,true);
			$string06 = $crypt->decryptBase64($string06,$key,true);
			$string07 = $crypt->decryptBase64($string07,$key,true);
			$string08 = $crypt->decryptBase64($string08,$key,true);
			$string09 = $crypt->decryptBase64($string09,$key,true);
			$string10 = $crypt->decryptBase64($string10,$key,true);

			$phql = "INSERT INTO Cuput
			(string01,string02,string03,string04,string05,string06,string07,string08,string09,string10)
			VALUES ('$string01','$string02','$string03','$string04','$string05','$string06','$string07','$string08','$string09','$string10')";
			$query = $this->modelsManager->createQuery($phql);
			$execute_query= $query->execute();


			echo "DONE\n";
			$time_end = microtime(true);
			$time = $time_end - $time_start;
			echo $time;
    }

		public function cypherjayputAction($string){
    	$time_start = microtime(true);

			$crypt = new Crypt();
			$key = "5//ba24cbea853/*d5ba24cbea85/*ssssssssss/**425ba24c//be/K9996a8542";
			
			$string = $crypt->decryptBase64($string,$key,true);

			echo "DONE\n";
			var_dump(json_decode($string, true));
			$time_end = microtime(true);
			$time = $time_end - $time_start;
			echo $time;
		}
}
