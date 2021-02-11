<?php

namespace App\Services;

use BaseModel;
use ContatoFormulario;
use Phalcon\Di;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class ContatoFormularioService
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
     * @return \stdClass
     */
    public function getSearchPage()
    {
        /**
         * @var \Phalcon\Http\Request $request
         */
        $request = $this->di->getRequest();

        /**
         * @var \Phalcon\Session\Adapter\Files $session
         */
        $session = $this->di->getSession();

        $limit = 50;
        if ($request->isPost()) {
            $numberPage = 1;
            $query = Criteria::fromInput($this->di, "usuario", $request->getPost());
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $request->getQuery("page", "int", 1);
        }

        $messages = $this->di->getModelsManager()
            ->createBuilder()
            ->from('ContatoFormulario')
            ->orderBy("ContatoFormulario.data_envio DESC");

        $paginator = new Paginator(array(
            "builder"  => $messages,
            "limit" => $limit,
            "page"  => $numberPage
        ));

        $paginate = $paginator->getPaginate();

        return $paginate;
    }
}