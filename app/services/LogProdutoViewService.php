<?php

namespace App\Services;

use Phalcon\Di;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class LogProdutoViewService
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

        $limit = 50;
        $numberPage = $request->get('page', 'int', 1);

        $builder = $this->di->getModelsManager()
            ->createBuilder()
            ->columns([
                'LogProdutoView.codigo_produto',
                'ProdutoCore.descricao_sys',
                'SUM(LogProdutoView.view_count) AS view_count'
            ])
            ->from('LogProdutoView')
            ->join('ProdutoCore', 'ProdutoCore.codigo_produto = LogProdutoView.codigo_produto')
            ->groupBy('LogProdutoView.codigo_produto')
            ->orderBy('view_count DESC');

        $paginator = new Paginator([
            'builder' => $builder,
            'page' => $numberPage,
            'limit' => $limit
        ]);

        return $paginator->getPaginate();
    }
}
