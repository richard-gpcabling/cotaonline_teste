<?php

namespace App\Services;

use Orcamento;
use Phalcon\Di;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class OrcamentoService
{
    /**
     * @var \Phalcon\DiInterface
     */
    private $di;

    public function __construct()
    {
        $this->di = Di\FactoryDefault::getDefault();
    }

    /**
     * @param \Phalcon\Session\BagInterface $persistent
     * @return array
     */
    public function getPageIndex($persistent)
    {
        /**
         * @var \Phalcon\Http\Request $request
         */
        $request = $this->di->getRequest();
        /**
         * @var \Phalcon\Session\Bag $session
         */
        $session = $this->di->getSession();

        $period = null;
        $initdate = null;
        $enddate  = null;
        $status = null;
        $numberPage = 1;
        $client = null;
        $userquery = null;
        $bind=[];
        $tabeladecusto ="";
        if ($request->isPost()) {
            $query = Criteria::fromInput($this->di, "Orcamento", $request->getPost());
            $persistent->searchParams = $query->getParams();
        } else { // set search filters
            $numberPage = $request->getQuery("page", "int", 1);
            $initdate = $request->getQuery('initdate');
            $enddate  = $request->getQuery('enddate');
            $status  = $request->getQuery('status');
            $client  = $request->getQuery('client');
            $period  = $request->getQuery('period');
            $userquery  = $request->getQuery('query');
        }

        $mysqlQuery ="";
        $bind=[];
        $parameters = array();

        if ($persistent->searchParams && !$persistent->resetSearchParams) {
            $parameters = $persistent->searchParams;
        }
        $now = date('Y-m-d H:i:s');
        $d=mktime(01, 01, 01, 01, 01, 2016);
        $start = date('Y-m-d H:i:s', $d);

        if ($initdate != null && $enddate != null) {
            $mysqlQuery = ' data_de_criacao BETWEEN :start: AND :end: ';
            $bind['start']=$initdate;
            $bind['end']=$enddate;
        } elseif ($initdate != null && $enddate == null) {
            $mysqlQuery = ' data_de_criacao BETWEEN :start: AND :end: ';
            $bind['start']=$initdate;
            $bind['end']=$now;
        } elseif ($initdate == null && $enddate != null) {
            $mysqlQuery = ' data_de_criacao BETWEEN :start: AND :end: ';
            $bind['start']=$start;
            $bind['end']=$enddate;
        }

        if ($status != null && $status != 'total') {
            if ($mysqlQuery!='') {
                $mysqlQuery.=' AND ';
            }
            $mysqlQuery .=  ' Orcamento.status like :status: ';
            $bind['status']= $status;
        }

        if ($userquery != null) {
            if ($mysqlQuery!='') {
                $mysqlQuery.=' AND ';
            }
            $mysqlQuery .=  '  ( ClienteCore.nome like :userquery: OR Usuario.name like :userquery: OR Orcamento.id like :userquery:) ';
            $bind['userquery']="%".$userquery."%";
        }

        if ($client != null) {
            if ($mysqlQuery!='') {
                $mysqlQuery.=' AND ';
            }
            $mysqlQuery .=  ' Usuario.codigo_cliente like :code: ';
            $bind['code']="%".$client."%";
        } // end set search filters

        if ($session->auth['orcamento_naorelacionado']) { // User has been granted access to all invoices
            $orcamento = $this->di->getModelsManager()
                ->createBuilder()
                ->from('Orcamento')
                ->join('Usuario', 'Orcamento.usuario_id=Usuario.id')
                ->join('ClienteCore', 'ClienteCore.clienteUcode=Usuario.codigo_cliente')
                ->where($mysqlQuery, $bind)
                ->orderBy('Orcamento.data_de_criacao DESC');
        } else { // User has only access to related clients invoices
            if ($mysqlQuery!='') {
                $mysqlQuery.=' AND ';
            }
            $mysqlQuery .=  '(Orcamento.usuario_id = :userId: or Usuario.vendedor=:userId:)';
            $bind['userId']= $session->auth['id'];
            $orcamento = $this->di->getModelsManager()
                ->createBuilder()
                ->from('Orcamento')
                ->join('Usuario', 'Orcamento.usuario_id=Usuario.id')
                ->join('ClienteCore', 'ClienteCore.clienteUcode=Usuario.codigo_cliente')
                ->where($mysqlQuery, $bind)
                ->orderBy('Orcamento.data_de_criacao DESC');
        }

        $paginator = new Paginator(array(
            "builder"  => $orcamento,
            "limit" => 25,
            "page"  => $numberPage
        ));

        $data = [
            'client' => $client,
            'query' => $userquery,
            'initdate' => $initdate,
            'enddate' => $enddate,
            'status' => $status,
            'period' => $period,
            'resetSearchParams' => false,
            'page' => $paginator->getPaginate(),
            'searchParams' => []
        ];

        return $data;
    }

    public function getPageIndexDashboard()
    {
        $count_orc_all = Orcamento::count();
        $count_orc_sav = Orcamento::count("status='Salvo'");
        $count_orc_pre = Orcamento::count("status='Só Preços'");
        $count_orc_neg = Orcamento::count("status='Em negociação'");
        $count_orc_apr = Orcamento::count("status='Aprovado'");
        $count_orc_per = Orcamento::count("status='Perdido'");
        $count_orc_star = Orcamento::count("tipo='1'");

        $sav_perc = intval($count_orc_sav*100/$count_orc_all);
        $pre_perc	= intval($count_orc_pre*100/$count_orc_all);
        $neg_perc	= intval($count_orc_neg*100/$count_orc_all);
        $apr_perc	= intval($count_orc_apr*100/$count_orc_all);
        $per_perc	= intval($count_orc_per*100/$count_orc_all);
        $star_perc = intval($count_orc_star*100/$count_orc_all);

        $count_orc = [
            0 => number_format($count_orc_all, 0, '', '.'),
            1 => number_format($count_orc_sav, 0, '', '.'), 2 => $sav_perc,
            3 => number_format($count_orc_pre, 0, '', '.'), 4 => $pre_perc,
            5 => number_format($count_orc_neg, 0, '', '.'), 6 => $neg_perc,
            7 => number_format($count_orc_apr, 0, '', '.'), 8 => $apr_perc,
            9 => number_format($count_orc_per, 0, '', '.'), 10 => $per_perc,
            11 => number_format($count_orc_star, 0, '', '.'), 12 => $star_perc
        ];

        return $count_orc;
    }
}
