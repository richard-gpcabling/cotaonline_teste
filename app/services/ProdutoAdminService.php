<?php

namespace App\Services;


use Phalcon\Di;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class ProdutoAdminService
{
    /**
     * @var \Phalcon\DiInterface
     */
    private $di;

    public function __construct()
    {
        $this->di = Di\FactoryDefault::getDefault();
    }

    public function getPageIndex()
    {
        /**
         * @var \Phalcon\Http\Request $request
         */
        $request = $this->di->getRequest();
        /**
         * @var \Phalcon\Session\Bag $session
         */
        $session = $this->di->getSession();

        $limit = 50;
        $numberPage = 1;
        $mysqlquery = null;
        if ($request->isPost()) {
            $query = Criteria::fromInput($this->di, "ProdutoCore", $request->getPost());
            $session->put('searchParams', $query->getParams());
        } else {
            $numberPage = $request->getQuery("page", "int", 1);
            $mysqlquery = $request->getQuery("query");
        }

        $parameters = array();
        if ($session->has('searchParams') && !$session->has('resetSearchParams')) {
            $parameters = $session->get('searchParams');
        }

        $produto = $this->di->getModelsManager()
            ->createBuilder()
            ->columns('ProdutoCore.*')
            ->from('ProdutoCore')
            ->where('ProdutoCore.status = 1');

        if (is_string($mysqlquery)) {
            $produto->where("ProdutoCore.descricao_sys like CONCAT('%', ?0, '%') OR ProdutoCore.codigo_produto like CONCAT('%', ?1, '%')", [
                $mysqlquery,
                $mysqlquery
            ]);
        }

        $paginator = new Paginator(array(
            "builder"  => $produto,
            "limit" => $limit,
            "page"  => $numberPage
        ));

        $page = $paginator->getPaginate();

        $session->set('resetSearchParams', false);

        if ($page->total_items < 1) {
            $this->di->get('flash')->notice("A busca não encontrou produtos.");
        }

        $data = [
            'userquery' => $mysqlquery,
            'page' => $page
        ];

        $session->set('searchParams', []);

        return $data;
    }
}