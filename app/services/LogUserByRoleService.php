<?php

namespace App\Services;

use Phalcon\Di;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class LogUserByRoleService
{
    /**
     * @var \Phalcon\DiInterface
     */
    private $di;

    public function __construct()
    {
        $this->di = Di\FactoryDefault::getDefault();
    }

    public function getPageAdminIndex()
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
                'LogProdutoUsuarioDateView.codigo_produto',
                'LogProdutoUsuarioDateView.usuario_id',
                'LogProdutoUsuarioDateView.view_count',
                'LogProdutoUsuarioDateView.date',
                'ProdutoCore.descricao_sys',
                'Usuario.name',
                'Usuario.email',
                'Usuario.vendedor'
            ])
            ->from('LogProdutoUsuarioDateView')
            ->join('ProdutoCore', 'ProdutoCore.codigo_produto = LogProdutoUsuarioDateView.codigo_produto')
            ->join('Usuario','Usuario.id = LogProdutoUsuarioDateView.usuario_id')
            ->orderBy('LogProdutoUsuarioDateView.id DESC');

        $paginator = new Paginator([
            'builder' => $builder,
            'page' => $numberPage,
            'limit' => $limit
        ]);

        return $paginator->getPaginate();
    }

    /**
     * @id_list string
     */
    public function getPageVendedorIndex($id_list)
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
                'LogProdutoUsuarioDateView.codigo_produto',
                'LogProdutoUsuarioDateView.usuario_id',
                'LogProdutoUsuarioDateView.view_count',
                'LogProdutoUsuarioDateView.date',
                'ProdutoCore.descricao_sys',
                'Usuario.name',
                'Usuario.email'
            ])
            ->from('LogProdutoUsuarioDateView')
            ->join('ProdutoCore', 'ProdutoCore.codigo_produto = LogProdutoUsuarioDateView.codigo_produto')
            ->join('Usuario','Usuario.id = LogProdutoUsuarioDateView.usuario_id')
            ->where('LogProdutoUsuarioDateView.usuario_id in '.$id_list)
            ->orderBy('LogProdutoUsuarioDateView.id DESC');

        $paginator = new Paginator([
            'builder' => $builder,
            'page' => $numberPage,
            'limit' => $limit
        ]);

        return $paginator->getPaginate();
    }
}
