<?php

namespace App\Services;

use Phalcon\Di;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class UsuarioService
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
     * Get page index
     *
     * @return array
     */
    public function getPageIndex()
    {
        $request = $this->di->getRequest();
        $session = $this->di->getSession();
        
        $numberPage = 1;
        $mysqlquery = null;
        if ($request->isPost()) {
            $mysqlquery = Criteria::fromInput($this->di, "cliente", $request->getPost());
            $this->persistent->searchParams = $mysqlquery->getParams();
        } else {
            $numberPage = $request->getQuery("page", "int", 1);
            if ($request->getQuery("responseType")) {
                $responseType = $request->getQuery("responseType");
            }
            $mysqlquery = $request->getQuery("query");
        }

        $parameters = [];
        if (isset($this->persistent->searchParams)) {
            $parameters = $this->persistent->searchParams;
        }
        $salesquery = '';
        if ($session->auth['role'] == "vendedor"
            || $session->auth['role'] == "cliente parceiro"
            || $session->auth['role'] == "cliente normal"
        ) {
            $salesquery = 'Usuario.vendedor ='.$session->auth['id'];
        }

        $usuario = $this->di->getModelsManager()
            ->createBuilder()
            ->columns('usuario1.*')
            ->addFrom('Usuario', 'usuario1')
            ->join('Usuario', 'usuario2.id = usuario1.vendedor', 'usuario2', 'LEFT')
            ->where('usuario1.status = 1')
            ->orderBy('id DESC');

        $bind = [];
        $bind['mysqlquery'] ="%".$mysqlquery."%";
        if ($usuario!==null && is_string($mysqlquery)) {
            if ($salesquery != '') {
                $usuario->where($salesquery . ' AND (usuario1.name like :mysqlquery: OR usuario1.email like :mysqlquery: 
                        OR usuario2.name like :mysqlquery: OR usuario2.email like :mysqlquery:)', $bind);
            } else {
                $usuario->where('usuario1.name like :mysqlquery: OR usuario1.email like :mysqlquery:', $bind);
            }
        } elseif ($usuario !== null && !is_string($mysqlquery)) {
            $usuario->where($salesquery);
        }

        $paginator = new Paginator(array(
            "builder"  => $usuario->andWhere("status!='inactive'"),
            "limit" => 120,
            "page"  => $numberPage
        ));

        $data['userquery'] = $mysqlquery;
        $data['page'] = $paginator->getPaginate();

        return $data;
    }
}
