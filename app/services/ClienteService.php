<?php

namespace App\Services;

use App\Forms\ClienteForm;
use App\Helpers\UtilHelper;
use Phalcon\Di;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class ClienteService
{
    /**
     * @var \Phalcon\DiInterface
     */
    private $di;

    public function __construct()
    {
        $this->di = Di\FactoryDefault::getDefault();
    }
    
    public function getPageIndex($persistent,$tipo=null)
    {
        /**
         * @var \Phalcon\Http\Request $request
         */
        $request = $this->di->getRequest();

        /**
         * @var \Phalcon\Session\Bag $session
         */
        $session = $this->di->getSession();
        
        $responseType = $request->getQuery("responseType", 'string', 'html');
        $numberPage = $request->getQuery("page", "int", 1);
        $mysqlquery = null;
        $clientrelated = false;
        if ($request->isPost()) {
            $mysqlquery = Criteria::fromInput($this->di, "cliente", $request->getPost());
            $persistent->searchParams = $mysqlquery->getParams();
        } else {
            $numberPage = $request->getQuery("page", "int", 1);
            if ($request->getQuery("responseType")) {
                $responseType = $request->getQuery("responseType");
            }
            $mysqlquery = $request->getQuery("query");
        }

        $parameters = array();
        if ($persistent->searchParams && !$persistent->resetSearchParams) {
            $parameters = $persistent->searchParams;
        }
        if (/*$session->auth['cliente_naorelacionado'] AND*/ $responseType == 'JSON') { // User has been granted access to all clients list
            $clientes = $this->di->getModelsManager()
                ->createBuilder()
                ->distinct(true)
                ->columns('ClienteCore.id,ClienteCore.clienteUcode,ClienteCore.nome,ClienteCore.cnpj_cpf')
                ->from('ClienteCore');
            $bind = [];
            $bind['mysqlquery'] = "%".$mysqlquery."%";
            if ($mysqlquery !== null && is_string($mysqlquery)) {
                if ($responseType=='JSON') {
                    $clientes->where('cnpj_cpf like :mysqlquery: OR nome like :mysqlquery:', $bind);
                } else {
                    $clientes->where('cnpj_cpf like :mysqlquery:', $bind);
                }
            }
        } else { // User has only access to related clients invoices
            $clientes = $this->di->getModelsManager()
                ->createBuilder()
                ->distinct(true)
                ->columns('ClienteCore.clienteUcode,
                    ClienteCore.razao_social,
                    ClienteCore.cnpj_cpf,
                    ClienteCore.canal,
                    ClienteCore.origem_de_compra,
                    ClienteCore.destino,
                    ClienteCore.vendedor,
                    ClienteCore.mark_up_geral,
                    ClienteTipoFiscal.tipo_fiscal as tipo_fiscal
                    ')
                ->join('ClienteTipoFiscal', 'ClienteTipoFiscal.id = ClienteCore.tipo_fiscal')
                ->orderBy('ClienteCore.codigo_policom ASC')
                ->from('ClienteCore');
            $bind = [];
            $bind['userId'] = $session->auth['id'];
            if ($mysqlquery !== null && is_string($mysqlquery)) {
                $bind['mysqlquery'] ="%".$mysqlquery."%";
                if ($responseType=='JSON') {
                    $clientes->where('cnpj_cpf like :mysqlquery: OR nome like :mysqlquery: OR vendedor like :mysqlquery:', $bind);
                } else {
                    $clientes->where('cnpj_cpf like :mysqlquery: OR nome like :mysqlquery: OR vendedor like :mysqlquery:', $bind);
                }
            } else {
                //$clientes->where('', $bind);
            }
        }
        
        $persistent->resetSearchParams = false;
        $persistent->searchParams = [];

        $paginator = new Paginator(array(
            "builder"  => $clientes,
            "limit" => 100,
            "page"  => $numberPage
        ));
        
        $page = $paginator->getPaginate();

        if ($page->total_items < 1 && $responseType != 'JSON') {
            $this->di->get('flash')->notice("A busca não encontrou clientes.");
        }

        $data = [
            'page' => $page,
            'responseType' => $responseType,
            'query' => $mysqlquery
        ];

        return $data;
    }
}
