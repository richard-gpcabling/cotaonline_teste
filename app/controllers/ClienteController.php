<?php

use App\Forms\ClienteForm;
use App\Services\ClienteService;
use App\Helpers\UtilHelper;
use Phalcon\Http\Response;
use Phalcon\Mvc\View;
use Phalcon\Di;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class ClienteController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Clientes');
        parent::initialize();
    }
    
    public function indexAction()
    {   
        $this->searchAction();
    }

    public function searchAction()
    {
        $service = new ClienteService();
        $data = $service->getPageIndex($this->persistent);

        //$this->view->form = $data['form'];
        $this->view->page = $data['page'];
        $this->view->query = $data['query'];

        if ($data['responseType'] == 'JSON') {
            $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
            $response = new Response();
            $response->setContent('{"ok":'.json_encode($data['page']).'}');
            return $response;
        }
    }

    public function newAction()
    {
        $associate= $this->request->getQuery("associate");
        $this->view->form = new ClienteForm(null, array('edit' => false,'associate' => $associate));
    }

    public function detailsAction($clienteUcode)
    {
        try {
            if (!$this->request->isPost()) {
            $cliente = ClienteCore::findFirstByClienteUcode($clienteUcode);
            $tipo_fiscal = ClienteTipoFiscal::findFirstById($cliente->tipo_fiscal);
            $cliente->tipo_fiscal = $tipo_fiscal->tipo_fiscal;

            if ($cliente->revendedor == $cliente->clienteUcode
                OR $cliente->revendedor == 'NULL'
                OR $cliente->revendedor == '0PO'):
                $cliente->revendedor = 'N/A';
            endif;

            if($cliente->mark_up_fabricantes != 'NULL'):
                $cliente->mark_up_fabricantes = json_decode($cliente->mark_up_fabricantes, true);
            endif;

            if($cliente->canal != 0):
                $clientes_atendidos = $this->di->getModelsManager()
                    ->createBuilder()
                    ->columns('
                        ClienteCore.clienteUcode,
                        ClienteCore.razao_social,
                        ClienteCore.cnpj_cpf,
                        ClienteCore.origem_de_compra,
                        ClienteCore.destino,
                        ClienteCore.vendedor,
                        ClienteCore.mark_up_geral
                        ')
                    ->from('ClienteCore')
                    ->where('ClienteCore.revendedor = :clienteUcode: 
                                AND ClienteCore.clienteUcode != :clienteUcode:',['clienteUcode'=>$clienteUcode])
                    ->orderBy('ClienteCore.codigo_policom ASC');
                
                $request = $this->di->getRequest();
                $numberPage = $request->getQuery("page", "int", 1);
                $paginator = new Paginator(array(
                    "builder"  => $clientes_atendidos,
                    "limit" => 50,
                    "page"  => $numberPage
                ));

                $page = $paginator->getPaginate();

                $this->view->page = $page;
            endif;

                  if (!$cliente) {
                    $this->flash->error("Cliente não encontrado.");

                    return $this->dispatcher->forward(
                    [
                        "controller" => "cliente",
                        "action"	 => "index",
                    ]
                );
                }

                $this->view->cliente = $cliente;
            }
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
                "controller" => "cliente",
                "action"	 => "index",
            ]
        );
    }
}
